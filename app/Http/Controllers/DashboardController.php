public function studentDashboard()
{
    $user = auth()->user();
    
    // Clubs the student has successfully joined
    $joinedClubs = Club::whereHas('members', function($query) use ($user) {
        $query->where('user_id', $user->id)->where('status', 'accepted');
    })->get();

    // Upcoming events from joined clubs
    $upcomingEvents = \App\Models\Event::whereIn('club_id', $joinedClubs->pluck('id'))
                        ->where('event_date', '>=', now())
                        ->orderBy('event_date', 'asc')
                        ->get();

    return view('dashboard.student', compact('joinedClubs', 'upcomingEvents'));
}