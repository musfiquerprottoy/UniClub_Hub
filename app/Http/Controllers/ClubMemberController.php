<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubMemberController extends Controller
{
    /**
     * Show the colorful application form.
     */
    public function showJoinForm(Club $club)
    {
        return view('clubs.join', compact('club'));
    }

    /**
     * Process the membership application.
     */
    public function processJoinRequest(Request $request, Club $club)
    {
        // 1. Validate all manual user inputs
        $request->validate([
            'student_name'     => 'required|string|max:255',
            'student_id_input' => 'required|string|max:50',
            'mobile_no'        => 'nullable|string|max:20',
            'semester'         => 'required|string|max:50',
            'applied_role'     => 'required|string|in:General Member,Executive',
        ]);

        // 2. Prevent duplicate applications from the same logged-in account
        $exists = ClubMember::where('club_id', $club->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return redirect()->route('clubs.show', $club->id)
                ->with('error', 'You have already submitted an application for this club.');
        }

        /**
         * 3. Create the record using manual inputs.
         * * Logic Mapping:
         * - user_id: Logged in account ID (system link)
         * - student_id: What the user typed in the form
         * - department: We store the 'student_name' here to satisfy NOT NULL
         * - address: We store the 'applied_role' here
         */
        ClubMember::create([
            'club_id'    => $club->id,
            'user_id'    => Auth::id(), 
            'student_id' => $request->student_id_input, 
            'status'     => 'pending',
            'mobile_no'  => $request->mobile_no ?? 'Not Provided',
            'semester'   => $request->semester,
            'department' => $request->student_name, 
            'address'    => 'Applied for: ' . $request->applied_role,
        ]);

        return redirect()->route('clubs.show', $club->id)
            ->with('success', 'Success! Application for ' . $request->student_name . ' has been sent.');
    }

    /**
     * Update status (Approve/Reject).
     */
    public function updateMemberStatus(Request $request, ClubMember $clubMember)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $clubMember->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Member status updated to ' . $request->status);
    }

    /**
     * Allow user to withdraw or leave.
     */
    public function leaveClub(Club $club)
    {
        ClubMember::where('club_id', $club->id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->route('clubs.show', $club->id)
            ->with('success', 'Application withdrawn/Club left successfully.');
    }
}