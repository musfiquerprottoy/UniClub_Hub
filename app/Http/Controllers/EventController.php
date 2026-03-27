<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Club;
use App\Models\Venue; // <-- Added this for the finalize method
use App\Notifications\NewEventProposal;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // ADVISOR: Reviews the proposal and sends it to the Admin
    public function review(Request $request, Event $event)
    {
        // Update status to 'approved' (meaning advisor approved)
        $event->update(['status' => 'approved']);

        // Notify Admins that it's ready for finalization
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewEventProposal($event)); 
        }

        return redirect('/dashboard')->with('success', 'Proposal reviewed and sent to Admin for final approval!');
    }

    // ADMIN: Finalizes the event, assigns a venue, checks for conflicts
    public function finalize(Request $request, Event $event)
    {
        // 1. Validate the Admin's input
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // 2. Conflict Detection Algorithm
        $conflict = Event::where('venue_id', $request->venue_id)
            ->where('status', 'scheduled') // Only check against already locked-in events
            ->where('start_time', '<', $request->end_time)
            ->where('end_time', '>', $request->start_time)
            ->first();

        // 3. If a conflict exists, stop and warn the Admin
        if ($conflict) {
            return redirect()->back()->with('error', '⚠️ Venue Conflict! This room is already booked for "' . $conflict->title . '" during that time.');
        }

        // 4. If no conflict, lock it in!
        $event->update([
            'venue_id' => $request->venue_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'scheduled' // Moving it out of the 'approved' queue into 'scheduled'
        ]);

        return redirect('/dashboard')->with('success', 'Event officially scheduled and venue secured!');
    }

    // EXECUTIVE: Shows the Event Proposal Form
    public function create()
    {
        // We need all clubs so the user can pick which club the event is for
        $clubs = Club::all(); 
        return view('events.create', compact('clubs'));
    }

    // EXECUTIVE: Saves the proposal to the database
    public function store(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
        ]);

        // 1. Save the event (default status in DB should be 'pending')
        $event = Event::create($request->all());

        // 2. Find all Advisors and send them the notification
        $advisors = User::where('role', 'advisor')->get();
        foreach ($advisors as $advisor) {
            $advisor->notify(new NewEventProposal($event));
        }

        return redirect('/dashboard')->with('success', 'Event proposal submitted successfully! Pending approval.');
    }
}