<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Club;
use App\Models\Venue;
use App\Notifications\NewEventProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * EXECUTIVE:
     * Show event creation form
     */
    public function create()
    {
        $clubs = Club::orderBy('name')->get();

        return view('events.create', compact('clubs'));
    }

    /**
     * EXECUTIVE:
     * Store new event proposal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_id'     => 'required|exists:clubs,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date|after:today',
            'location'    => 'required|string|max:255',

            // Image upload
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        /**
         * Upload image if exists
         */
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('event-images', 'public');

            $validated['image'] = $path;
        }

        /**
         * Default values
         */
        $validated['status'] = 'pending';
        $validated['created_by'] = auth()->id();

        /**
         * Create event
         */
        $event = Event::create($validated);

        /**
         * Notify advisors
         */
        $advisors = User::where('role', 'advisor')->get();

        foreach ($advisors as $advisor) {
            $advisor->notify(new NewEventProposal($event));
        }

        return redirect('/dashboard')
            ->with('success', 'Event proposal submitted successfully! Pending approval.');
    }

    /**
     * ADVISOR:
     * Review & approve event
     */
    public function review(Request $request, Event $event)
    {
        $event->update([
            'status' => 'approved'
        ]);

        /**
         * Notify admins
         */
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewEventProposal($event));
        }

        return redirect('/dashboard')
            ->with('success', 'Proposal reviewed and sent to Admin for final approval!');
    }

    /**
     * ADMIN:
     * Finalize & schedule event
     */
    public function finalize(Request $request, Event $event)
    {
        /**
         * Validate input
         */
        $request->validate([
            'venue_id'  => 'required|exists:venues,id',
            'start_time'=> 'required|date',
            'end_time'  => 'required|date|after:start_time',
        ]);

        /**
         * Venue conflict detection
         */
        $conflict = Event::where('venue_id', $request->venue_id)
            ->where('status', 'scheduled')
            ->where('start_time', '<', $request->end_time)
            ->where('end_time', '>', $request->start_time)
            ->first();

        /**
         * Stop if conflict exists
         */
        if ($conflict) {
            return redirect()->back()->with(
                'error',
                '⚠️ Venue Conflict! This room is already booked for "' .
                $conflict->title .
                '" during that time.'
            );
        }

        /**
         * Finalize event
         */
        $event->update([
            'venue_id'  => $request->venue_id,
            'start_time'=> $request->start_time,
            'end_time'  => $request->end_time,
            'status'    => 'scheduled',
        ]);

        return redirect('/dashboard')
            ->with('success', 'Event officially scheduled and venue secured!');
    }

    /**
     * List events
     */
    public function index()
    {
        $user = auth()->user();

        $events = Event::with(['club', 'venue'])
            ->latest()
            ->when($user->role === 'executive', function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    /**
     * Edit event
     */
    public function edit(Event $event)
    {
        $clubs = Club::orderBy('name')->get();

        return view('events.edit', compact('event', 'clubs'));
    }

    /**
     * Update event
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

        /**
         * Replace image if uploaded
         */
        if ($request->hasFile('image')) {

            // Delete old image
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }

            $validated['image'] = $request
                ->file('image')
                ->store('event-images', 'public');
        }

        $event->update($validated);

        return redirect()->back()
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        /**
         * Delete image
         */
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->back()
            ->with('success', 'Event deleted successfully!');
    }
}