<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Club;
use App\Models\Venue;
use App\Notifications\NewEventProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * EXECUTIVE: Show event creation form
     */
    public function create()
    {
        // Executives should only see clubs they are assigned to manage
        $clubs = Club::where('user_id', Auth::id())->orderBy('name')->get();

        return view('events.create', compact('clubs'));
    }

    /**
     * EXECUTIVE: Store new event proposal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_id'     => 'required|exists:clubs,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date|after:today',
            'location'    => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('event-images', 'public');
        }

        // Initialize status and ownership
        $validated['status'] = 'pending_advisor';
        $validated['created_by'] = Auth::id();

        $event = Event::create($validated);

        // Notify Advisors
        $advisors = User::where('role', 'advisor')->get();
        foreach ($advisors as $advisor) {
            // Ensure you have created this Notification class
            $advisor->notify(new NewEventProposal($event));
        }

        return redirect()->route('dashboard')
            ->with('success', 'Event Proposal submitted successfully! It is now awaiting Advisor review.');
    }

    /**
     * ADVISOR: Review & forward to Admin
     */
    public function forward(Request $request, Event $event)
    {
        $event->update(['status' => 'pending_admin']);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewEventProposal($event));
        }

        return redirect()->route('dashboard')
            ->with('success', 'Event Proposal forwarded to Admin successfully.');
    }

    /**
     * ADMIN: Simple Approval (if no venue/time scheduling is needed immediately)
     */
    public function approve(Request $request, Event $event)
    {
        $event->update(['status' => 'approved']);

        return redirect()->route('dashboard')
            ->with('success', 'Event has been approved and is now Live!');
    }

    /**
     * ADMIN: Finalize with Venue & Time (Includes Conflict Detection)
     */
    public function finalize(Request $request, Event $event)
    {
        $request->validate([
            'venue_id'   => 'required|exists:venues,id',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
        ]);

        // Conflict Detection: Check if any OTHER approved event uses this venue at this time
        $conflict = Event::where('venue_id', $request->venue_id)
            ->where('id', '!=', $event->id) // Don't check against itself
            ->where('status', 'approved') 
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->first();

        if ($conflict) {
            return redirect()->back()->with(
                'error',
                '⚠️ Venue Conflict! This room is already booked for "' . $conflict->title . '".'
            );
        }

        $event->update([
            'venue_id'   => $request->venue_id,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'status'     => 'approved', 
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Event officially scheduled and venue secured!');
    }

    /**
     * List events for the public/users
     */
    public function index()
    {
        $user = Auth::user();

        $events = Event::with(['club', 'venue'])
            ->latest()
            ->when($user->role === 'executive', function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->when($user->role === 'student', function ($query) {
                // Students only see final, approved events
                $query->where('status', 'approved');
            })
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    /**
     * Edit event proposal (Usually for Executives before approval)
     */
    public function edit(Event $event)
    {
        // Only owner can edit
        if ($event->created_by !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $clubs = Club::where('user_id', Auth::id())->orderBy('name')->get();
        return view('events.edit', compact('event', 'clubs'));
    }

    /**
     * Update event proposal
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'club_id'     => 'required|exists:clubs,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'location'    => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('event-images', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Delete event proposal
     */
    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->back()
            ->with('success', 'Event deleted successfully!');
    }
}