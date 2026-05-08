<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMember;
use Illuminate\Http\Request;

class ClubMemberController extends Controller
{
    // Student submits the join form
    public function processJoinRequest(Request $request, Club $club) {
        $request->validate([
            'student_id' => 'required',
            'mobile_no' => 'required',
            'department' => 'required',
            'semester' => 'required',
        ]);
    
        ClubMember::create([
            'user_id' => auth()->id(),
            'club_id' => $club->id,
            'student_id' => $request->student_id,
            'mobile_no' => $request->mobile_no,
            'department' => $request->department,
            'semester' => $request->semester,
            'address' => $request->address,
            'status' => 'pending',
        ]);
    
        // Notify the Executive
        if($club->user) { // Assuming 'user' is the manager/executive on the Club model
            $club->user->notify(new \App\Notifications\ClubApplicationNotification(
                'New Member Request',
                auth()->user()->name . " wants to join " . $club->name,
                '👤',
                route('clubs.manage-members', $club->id)
            ));
        }
    
        return redirect()->route('dashboard')->with('success', 'Join request sent!');
    }
    
    // Executive Accepts/Rejects a student
    public function updateMemberStatus(Request $request, ClubMember $member) {
        $status = $request->status; // 'accepted' or 'rejected'
        $member->update(['status' => $status]);
    
        // 1. Notify the Student
        $member->user->notify(new \App\Notifications\ClubApplicationNotification(
            'Membership Update',
            "Your request to join " . $member->club->name . " was " . $status,
            $status == 'accepted' ? '🎉' : '❌'
        ));
    
        // 2. Notify the Advisors
        $advisors = \App\Models\User::where('role', 'advisor')->get();
        foreach($advisors as $advisor) {
            $advisor->notify(new \App\Notifications\ClubApplicationNotification(
                'Member Enrollment',
                auth()->user()->name . " $status " . $member->user->name . " for " . $member->club->name,
                'ℹ️'
            ));
        }
    
        return back()->with('success', 'Status updated and notifications sent.');
    }
}