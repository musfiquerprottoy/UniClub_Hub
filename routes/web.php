<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ClubMemberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // --- CLUBS ---
    
    // 1. Admin Specific (System Management)
    Route::middleware('role:admin')->group(function () {
        Route::get('/clubs/create', [ClubController::class, 'create'])->name('clubs.create');
        Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
        Route::delete('/clubs/{club}', [ClubController::class, 'destroy'])->name('clubs.destroy');
        
        // Final Event Approval & Room Booking
        Route::patch('/events/{event}/approve', [EventController::class, 'approve'])->name('events.approve');
        Route::patch('/events/{event}/finalize', [EventController::class, 'finalize'])->name('events.finalize');
    });

    // 2. General Public List & Details
    Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::get('/clubs/{club}', [ClubController::class, 'show'])->name('clubs.show');

    // 3. Executive Actions (Manage current clubs or apply for new ones)
    Route::middleware('role:executive')->group(function () {
        // Request Management from an Advisor
        Route::post('/clubs/{club}/apply', [ClubController::class, 'apply'])->name('clubs.apply');
        
        // Manage Members of their own clubs
        Route::get('/clubs/{club}/manage-members', [ClubController::class, 'manageMembers'])->name('clubs.manage-members');
        Route::patch('/members/{clubMember}/status', [ClubMemberController::class, 'updateMemberStatus'])->name('members.update-status');

        // Edit Club Profile
        Route::get('/clubs/{club}/edit', [ClubController::class, 'edit'])->name('clubs.edit');
        Route::patch('/clubs/{club}', [ClubController::class, 'update'])->name('clubs.update');

        // Propose New Events
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
    });

    // 4. Student Actions (Joining/Leaving)
    Route::middleware('role:student')->group(function () {
        Route::get('/clubs/{club}/join', [ClubMemberController::class, 'showJoinForm'])->name('clubs.join');
        Route::post('/clubs/{club}/join', [ClubMemberController::class, 'processJoinRequest'])->name('clubs.join.process');
        Route::delete('/clubs/{club}/leave', [ClubMemberController::class, 'leaveClub'])->name('clubs.leave');
    });

    // 5. Advisor Actions (Approval Workflow)
    Route::middleware('role:advisor')->group(function () {
        // Management Applications (Matching dashboard buttons)
        Route::patch('/applications/{application}/approve', [ClubController::class, 'approveApplication'])->name('applications.approve');
        Route::patch('/applications/{application}/reject', [ClubController::class, 'rejectApplication'])->name('applications.reject');
        
        // Event Review
        Route::patch('/events/{event}/forward', [EventController::class, 'forward'])->name('events.forward');
    });

    // --- EVENTS (General) ---
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // --- NOTIFICATIONS ---
    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');

    Route::post('/notifications/mark-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    })->name('notifications.markAllAsRead');

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';