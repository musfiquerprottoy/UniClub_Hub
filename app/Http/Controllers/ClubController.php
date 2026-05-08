<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Models\ClubMember;
use App\Models\ClubApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ClubApplicationNotification;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::withCount(['members as accepted_members_count' => function($query) {
            $query->where('status', 'accepted');
        }])->latest()->get();

        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('clubs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string',
            'creation_date' => 'required|date',
            'category'      => 'required|string|max:255',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('club-logos', 'public');
        }

        Club::create([
            'name'          => $validated['name'],
            'description'   => $validated['description'],
            'creation_date' => $validated['creation_date'],
            'category'      => $validated['category'],
            'logo'          => $validated['logo'] ?? null,
            'user_id'       => Auth::id(), // Initially owned by creator
        ]);

        return redirect()->route('dashboard')->with('success', 'New club established successfully!');
    }

    public function show(Club $club)
    {
        // Added 'manager' and 'accepted_members_count' loading
        $club->load(['events', 'manager', 'applications.executive', 'members.user']);
        return view('clubs.show', compact('club'));
    }

    /**
     * Executive applies to manage a club.
     */
    public function apply(Request $request, Club $club)
    {
        $request->validate([
            'advisor_id' => 'required|exists:users,id'
        ]);

        // Prevent managing own club
        if ($club->user_id === Auth::id()) {
            return back()->with('error', 'You already manage this club.');
        }

        // Check for existing pending application
        $alreadyApplied = ClubApplication::where('user_id', Auth::id())
            ->where('club_id', $club->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Management application is already pending.');
        }

        $application = ClubApplication::create([
            'user_id'    => Auth::id(),
            'advisor_id' => $request->advisor_id,
            'club_id'    => $club->id,
            'status'     => 'pending',
        ]);

        // Notify Advisor
        $advisor = User::find($request->advisor_id);
        if ($advisor) {
            $advisor->notify(new ClubApplicationNotification(
                'New Club Application',
                Auth::user()->name . " wants to manage " . $club->name,
                '📝',
                route('dashboard')
            ));
        }

        return back()->with('success', 'Application submitted to ' . $advisor->name);
    }

    /**
     * Advisor Approves Club Management Request
     */
    public function approveApplication(ClubApplication $application)
    {
        // 1. Update Application status
        $application->update(['status' => 'approved']);
        
        // 2. Transfer Club Ownership to the applicant
        $application->club->update(['user_id' => $application->user_id]);

        // 3. Notify the Executive (Applicant)
        // Note: Ensure the 'executive' relationship is defined in ClubApplication model
        if ($application->executive) {
            $application->executive->notify(new ClubApplicationNotification(
                'Application Approved',
                "You are now the manager of " . $application->club->name,
                '✅',
                route('dashboard')
            ));
        }

        return redirect()->route('dashboard')->with('success', 'Management transferred successfully!');
    }

    public function rejectApplication(ClubApplication $application)
    {
        $application->update(['status' => 'rejected']);
        
        if ($application->executive) {
            $application->executive->notify(new ClubApplicationNotification(
                'Application Rejected',
                "Your request to manage " . $application->club->name . " was declined.",
                '❌',
                route('dashboard')
            ));
        }

        return redirect()->route('dashboard')->with('success', 'Application rejected.');
    }

    public function manageMembers(Club $club)
    {
        // Security check
        if ($club->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $pendingMembers = ClubMember::where('club_id', $club->id)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return view('clubs.manage-members', compact('pendingMembers', 'club'));
    }

    public function edit(Club $club)
    {
        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
        ]);

        $club->update($validated);
        return redirect()->route('clubs.show', $club)->with('success', 'Club updated.');
    }

    public function destroy(Club $club)
    {
        $club->delete();
        return redirect()->route('clubs.index')->with('success', 'Club removed.');
    }
}