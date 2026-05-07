<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClubController extends Controller
{
    /**
     * Display all clubs
     */
    public function index()
    {
        $clubs = Club::latest()->get();

        return view('clubs.index', compact('clubs'));
    }

    /**
     * Show create club form
     */
    public function create()
    {
        return view('clubs.create');
    }

    /**
     * Store newly created club
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

        /**
         * Upload logo if exists
         */
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('club-logos', 'public');
            $validated['logo'] = $path;
        }

        /**
         * Create club
         */
        Club::create([
            'name'          => $validated['name'],
            'description'   => $validated['description'],
            'creation_date' => $validated['creation_date'],
            'category'      => $validated['category'],
            'logo'          => $validated['logo'] ?? null,
            'user_id'       => Auth::id(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'New club established successfully!');
    }

    /**
     * Display specific club
     */
    public function show(Club $club)
    {
        // Load relationships
        $club->load(['events', 'manager']);

        return view('clubs.show', compact('club'));
    }

    /**
     * Executive applies to manage a club
     */
    public function apply(Club $club)
    {
        /**
         * Already manager
         */
        if ($club->user_id === Auth::id()) {
            return back()->with(
                'error',
                'You already manage this club.'
            );
        }

        /**
         * Duplicate application prevention
         */
        $alreadyApplied = ClubApplication::where('user_id', Auth::id())
            ->where('club_id', $club->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyApplied) {
            return back()->with(
                'error',
                'You already applied for this club.'
            );
        }

        /**
         * Create application
         */
        ClubApplication::create([
            'user_id' => Auth::id(),
            'club_id' => $club->id,
            'status'  => 'pending',
        ]);

        return back()->with(
            'success',
            'Application submitted successfully! Waiting for advisor approval.'
        );
    }

    /**
     * Advisor approves executive request
     */
    public function approveApplication(ClubApplication $application)
    {
        /**
         * Update application status
         */
        $application->update([
            'status' => 'approved',
        ]);

        /**
         * Assign executive as club manager
         */
        $application->club->update([
            'user_id' => $application->user_id,
        ]);

        return back()->with(
            'success',
            'Executive successfully assigned to ' . $application->club->name
        );
    }

    /**
     * Advisor rejects application
     */
    public function rejectApplication(ClubApplication $application)
    {
        $application->delete();

        return back()->with(
            'success',
            'Application rejected successfully.'
        );
    }

    /**
     * Optional edit page
     */
    public function edit(Club $club)
    {
        return view('clubs.edit', compact('club'));
    }

    /**
     * Optional update method
     */
    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'required|string',
            'creation_date' => 'required|date',
            'category'      => 'required|string|max:255',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024',
        ]);

        /**
         * Replace logo if new one uploaded
         */
        if ($request->hasFile('logo')) {

            // Delete old logo
            if ($club->logo && Storage::disk('public')->exists($club->logo)) {
                Storage::disk('public')->delete($club->logo);
            }

            // Store new logo
            $validated['logo'] = $request
                ->file('logo')
                ->store('club-logos', 'public');
        }

        $club->update($validated);

        return redirect()
            ->route('clubs.show', $club)
            ->with('success', 'Club updated successfully!');
    }

    /**
     * Delete club
     */
    public function destroy(Club $club)
    {
        /**
         * Delete logo
         */
        if ($club->logo && Storage::disk('public')->exists($club->logo)) {
            Storage::disk('public')->delete($club->logo);
        }

        $club->delete();

        return redirect()
            ->route('clubs.index')
            ->with('success', 'Club deleted successfully!');
    }
}