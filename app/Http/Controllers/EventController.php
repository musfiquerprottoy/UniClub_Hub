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
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * List events for the public/users
     */
    public function index()
    {
        $user = Auth::user();

        // 1. If Executive: Show their own proposals (so they can track status)
        // 2. Everyone else (Students/Guests): Show ONLY Approved events
        $events = Event::with(['club', 'venue'])
            ->latest()
            ->when($user && $user->role === 'executive', function ($query) use ($user) {
                $query->where('created_by', $user->id); 
            })
            ->when(!$user || $user->role !== 'executive', function ($query) {
                $query->where('status', 'approved');
            })
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $clubs = Club::where('user_id', Auth::id())->orderBy('name')->get();
        return view('events.create', compact('clubs'));
    }

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

        $validated['status'] = 'pending_advisor';
        $validated['created_by'] = Auth::id(); 

        $event = Event::create($validated);

        try {
            $advisors = User::where('role', 'advisor')->get();
            foreach ($advisors as $advisor) {
                if (class_exists(NewEventProposal::class)) {
                    $advisor->notify(new NewEventProposal($event));
                }
            }
        } catch (\Exception $e) {
            Log::error("Notification failed: " . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Event Proposal submitted! Awaiting Advisor review.');
    }

    public function forward(Request $request, Event $event)
    {
        $event->update(['status' => 'pending_admin']);
        return redirect()->route('dashboard')->with('success', 'Event Proposal forwarded to Admin.');
    }

    public function reject(Request $request, Event $event)
    {
        $event->update(['status' => 'rejected']);
        return redirect()->route('dashboard')->with('success', 'Event proposal has been rejected.');
    }

    public function approve(Request $request, Event $event)
    {
        $event->update(['status' => 'approved']);
        return redirect()->route('dashboard')->with('success', 'Event approved! It is now visible to the public.');
    }

    public function finalize(Request $request, Event $event)
    {
        $request->validate([
            'venue_id'   => 'required|exists:venues,id',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
        ]);

        $conflict = Event::where('venue_id', $request->venue_id)
            ->where('id', '!=', $event->id)
            ->where('status', 'approved') 
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->first();

        if ($conflict) {
            return redirect()->back()->with('error', '⚠️ Venue Conflict! This room is already booked.');
        }

        $event->update([
            'venue_id'   => $request->venue_id,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'status'     => 'approved', 
        ]);

        return redirect()->route('dashboard')->with('success', 'Event officially scheduled!');
    }

    public function edit(Event $event)
    {
        if ($event->created_by !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $clubs = (Auth::user()->role === 'admin') 
            ? Club::orderBy('name')->get() 
            : Club::where('user_id', Auth::id())->orderBy('name')->get();

        return view('events.edit', compact('event', 'clubs'));
    }

    public function update(Request $request, Event $event)
    {
        Log::info('Updating event ID ' . $event->id, $request->all());

        $validated = $request->validate([
            'club_id'     => 'required|exists:clubs,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'location'    => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) Storage::disk('public')->delete($event->image);
            $validated['image'] = $request->file('image')->store('event-images', 'public');
        }

        $event->update($validated);

        return redirect()->route('dashboard')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->image) Storage::disk('public')->delete($event->image);
        $event->delete();
        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
}