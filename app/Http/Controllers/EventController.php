<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewEventProposal;
use App\Models\Event;
use App\Models\Club;
use Illuminate\Http\Request;// You can reuse or create a new one

class EventController extends Controller
{
    public function review(Request $request, Event $event)
    {
        // Update status to 'approved' (or you could add a 'reviewed' status)
        $event->update(['status' => 'approved']);

        // Notify Admins that it's ready for finalization
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewEventProposal($event)); 
        }

        return redirect('/dashboard')->with('success', 'Proposal reviewed and sent to Admin for final approval!');
    }
    // Shows the Event Proposal Form
    public function create()
    {
        // We need all clubs so the user can pick which club the event is for
        $clubs = Club::all(); 
        return view('events.create', compact('clubs'));
    }

    // Saves the proposal to the database
    public function store(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
        ]);

        // 1. Save the event
        $event = Event::create($request->all());

        // 2. Find all Advisors and send them the notification
        $advisors = User::where('role', 'advisor')->get();
        foreach ($advisors as $advisor) {
            $advisor->notify(new NewEventProposal($event));
        }

        return redirect('/dashboard')->with('success', 'Event proposal submitted successfully! Pending approval.');
    }
}