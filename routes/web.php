<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ClubMemberController;

// --- PUBLIC ACCESS ---
Route::get('/', function () {
    return view('auth.login');
});

// GLOBALLY SHOWN: All users (including guests) can see events
Route::get('/events', [EventController::class, 'index'])->name('events.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // --- 1. ADMIN SPECIFIC ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/clubs/create', [ClubController::class, 'create'])->name('clubs.create');
        Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
        Route::delete('/clubs/{club}', [ClubController::class, 'destroy'])->name('clubs.destroy');
        
        Route::patch('/events/{event}/approve', [EventController::class, 'approve'])->name('events.approve');
        Route::patch('/events/{event}/finalize', [EventController::class, 'finalize'])->name('events.finalize');
    });

    // --- 2. ADVISOR ACTIONS ---
    Route::middleware('role:advisor')->group(function () {
        Route::patch('/applications/{application}/approve', [ClubController::class, 'approveApplication'])->name('applications.approve');
        Route::patch('/applications/{application}/reject', [ClubController::class, 'rejectApplication'])->name('applications.reject');
        
        // ADVISOR EVENT MANAGEMENT
        Route::patch('/events/{event}/forward', [EventController::class, 'forward'])->name('events.forward');
        Route::patch('/events/{event}/reject', [EventController::class, 'reject'])->name('events.reject');
    });

    // --- 3. EXECUTIVE ACTIONS ---
    Route::middleware('role:executive')->group(function () {
        Route::post('/clubs/{club}/apply', [ClubController::class, 'apply'])->name('clubs.apply');
        Route::get('/clubs/{club}/manage-members', [ClubController::class, 'manageMembers'])->name('clubs.manage-members');
        Route::patch('/members/{clubMember}/status', [ClubMemberController::class, 'updateMemberStatus'])->name('members.update-status');

        Route::get('/clubs/{club}/edit', [ClubController::class, 'edit'])->name('clubs.edit');
        Route::patch('/clubs/{club}', [ClubController::class, 'update'])->name('clubs.update');

        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
    });

    // --- 4. STUDENT ACTIONS ---
    Route::middleware('role:student')->group(function () {
        Route::get('/clubs/{club}/join', [ClubMemberController::class, 'showJoinForm'])->name('clubs.join');
        Route::post('/clubs/{club}/join', [ClubMemberController::class, 'processJoinRequest'])->name('clubs.join.process');
        Route::delete('/clubs/{club}/leave', [ClubMemberController::class, 'leaveClub'])->name('clubs.leave');
    });

    // --- 5. SHARED ACCESS (AUTHENTICATED) ---
    Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::get('/clubs/{club}', [ClubController::class, 'show'])->name('clubs.show');

    // SHARED EVENT ACTIONS (Creator/Admin)
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // --- 6. NOTIFICATIONS & PROFILE ---
    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');

    Route::post('/notifications/mark-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    })->name('notifications.markAllAsRead');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';