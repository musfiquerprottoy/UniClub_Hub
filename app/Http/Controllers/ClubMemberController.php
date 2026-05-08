<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubMemberController extends Controller
{
    public function showRequest($id)
    {
        $request = ClubMember::with(['user', 'club'])->findOrFail($id);
        
        if ($request->club->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('members.show-request', compact('request'));
    }

    public function showJoinForm(Club $club)
    {
        return view('clubs.join', compact('club'));
    }

    public function processJoinRequest(Request $request, Club $club)
    {
        $request->validate([
            'student_name'     => 'required|string|max:255',
            'student_id_input' => 'required|string|max:50',
            'mobile_no'        => 'nullable|string|max:20',
            'semester'         => 'required|string|max:50',
            'applied_role'     => 'required|string',
        ]);

        $exists = ClubMember::where('club_id', $club->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return redirect()->route('clubs.show', $club->id)
                ->with('error', 'You have already applied.');
        }

        // FIXED MAPPING: Correct columns assigned here
        ClubMember::create([
            'club_id'    => $club->id,
            'user_id'    => Auth::id(), 
            'full_name'  => $request->student_name,    // Correct column
            'student_id' => $request->student_id_input, 
            'mobile_no'  => $request->mobile_no ?? 'Not Provided',
            'semester'   => $request->semester,
            'department' => 'N/A',                     // Placeholder
            'address'    => 'Applied for: ' . $request->applied_role,
            'status'     => 'pending',
        ]);

        return redirect()->route('clubs.show', $club->id)
            ->with('success', 'Application sent successfully!');
    }

    public function updateMemberStatus(Request $request, $id)
    {
        $clubMember = ClubMember::findOrFail($id);

        // Accept 'active' or 'rejected' from the form buttons
        $request->validate([
            'status' => 'required|in:active,rejected',
        ]);

        $clubMember->update([
            'status' => $request->status
        ]);

        return redirect()->route('dashboard')->with('success', 'Status updated to ' . $request->status);
    }

    public function leaveClub(Club $club)
    {
        ClubMember::where('club_id', $club->id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->route('clubs.show', $club->id)
            ->with('success', 'Withdrawn successfully.');
    }
}