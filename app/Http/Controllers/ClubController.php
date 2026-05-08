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
    /**
     * Display a listing of all clubs.
     */
    public function index()
    {
        $clubs = Club::withCount(['members as accepted_members_count' => function($query) {
            $query->where('status', 'accepted');
        }])->latest()->get();

        return view('clubs.index', compact('clubs'));
    }

    /**
     * Show the form for creating a new club (Admin only).
     */
    public function create()
    {
        return view('clubs.create');
    }

    /**
     * Store a newly created club in storage.
     */
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
            'user_id'       => Auth::id(), // Owner is the creator (usually Admin)
        ]);

        return redirect()->route('dashboard')->with('success', 'New club established successfully!');
    }

    /**
     * Display the specific club details.
     */
    public function show(Club $club)
    {
        // Load relationships and count accepted members for the sidebar
        $club->load(['events', 'manager', 'members.user']);
        $club->loadCount(['members as accepted_members_count' => function($query) {
            $query->where('status', 'accepted');
        }]);

        return view('clubs.show', compact('club'));
    }

    /**
     * Executive applies to an Advisor to manage a club.
     */
    public function apply(Request $request, Club $club)
    {
        $request->validate([
            'advisor_id' => 'required|exists:users,id'
        ]);

        // 1. Prevent managing own club
        if ($club->user_id === Auth::id()) {
            return back()->with('error', 'You already manage this club.');
        }

        // 2. Check for existing pending application
        $alreadyApplied = ClubApplication::where('user_id', Auth::id())
            ->where('club_id', $club->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Management application is already pending.');
        }

        // 3. Create Application
        $application = ClubApplication::create([
            'user_id'    => Auth::id(),
            'advisor_id' => $request->advisor_id,
            'club_id'    => $club->id,
            'status'     => 'pending',
        ]);

        // 4. Notify Advisor
        $advisor = User::find($request->advisor_id);
        if ($advisor) {
            $advisor->notify(new ClubApplicationNotification(
                'New Club Application',
                Auth::user()->name . " wants to manage " . $club->name,
                '📝',
                route('dashboard')
            ));
        }

        // Updated success message
        return back()->with('success', 'Request Sent Successfully');
    }

    /**
     * Advisor Approves Club Management Request.
     */
    public function approveApplication(ClubApplication $application)
    {
        // Security: Ensure only the assigned advisor can approve
        if (Auth::id() !== $application->advisor_id) {
            abort(403, 'Unauthorized action.');
        }

        // 1. Update Application status
        $application->update(['status' => 'approved']);
        
        // 2. Transfer Club Ownership (Manager role) to the applicant
        $application->club->update(['user_id' => $application->user_id]);

        // 3. Notify the Executive
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

    /**
     * Advisor Rejects Club Management Request.
     */
    public function rejectApplication(ClubApplication $application)
    {
        // Security: Ensure only the assigned advisor can reject
        if (Auth::id() !== $application->advisor_id) {
            abort(403, 'Unauthorized action.');
        }

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

    /**
     * Executive manages pending member requests for their club.
     */
    public function manageMembers(Club $club)
    {
        // Security: Only the Manager (Executive) or Admin can manage members
        if ($club->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $pendingMembers = ClubMember::where('club_id', $club->id)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return view('clubs.manage-members', compact('pendingMembers', 'club'));
    }

    /**
     * Show the form for editing the club.
     */
    public function edit(Club $club)
    {
        // Security: Only Manager or Admin
        if ($club->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        return view('clubs.edit', compact('club'));
    }

    /**
     * Update the club details.
     */
    public function update(Request $request, Club $club)
    {
        // Security: Only Manager or Admin
        if ($club->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'logo' => 'nullable|image|max:1024'
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($club->logo) {
                Storage::disk('public')->delete($club->logo);
            }
            $validated['logo'] = $request->file('logo')->store('club-logos', 'public');
        }

        $club->update($validated);
        return redirect()->route('clubs.show', $club)->with('success', 'Club updated successfully.');
    }

    /**
     * Remove the club (Admin only).
     */
    public function destroy(Club $club)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($club->logo) {
            Storage::disk('public')->delete($club->logo);
        }

        $club->delete();
        return redirect()->route('clubs.index')->with('success', 'Club removed.');
    }
}