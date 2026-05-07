<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Guest Landing Page
Route::get('/', function () {
    return view('auth.login');
});

// 2. Dashboard (Shared entry point)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Protected Routes (Require Login)
Route::middleware('auth')->group(function () {

    // --- CLUBS ---
    
    // Admin Only: Creating and Storing Clubs
    Route::middleware('admin')->group(function () {
        Route::get('/clubs/create', [ClubController::class, 'create'])->name('clubs.create');
        Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
    });
    
    // General Club Access
    Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::get('/clubs/{club}', [ClubController::class, 'show'])->name('clubs.show');

    // Executive Only: Apply to manage a club (The missing route fix)
    Route::post('/clubs/{club}/apply', [ClubController::class, 'apply'])
        ->name('clubs.apply')
        ->middleware('executive');


    // --- CLUB APPLICATIONS (Management Approval) ---
    
    // Advisor Only: Approve or Reject Executive applications
    Route::middleware('advisor')->group(function () {
        Route::patch('/applications/{application}/approve', [ClubController::class, 'approveApplication'])
            ->name('clubs.approve-application');
            
        Route::delete('/applications/{application}/reject', [ClubController::class, 'rejectApplication'])
            ->name('clubs.reject-application');
    });


    // --- EVENTS ---
    
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    
    // Executive Only: Propose Events for managed clubs
    Route::middleware('executive')->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
    });

    // Advisor Only: Technical/Financial Review
    Route::patch('/events/{event}/review', [EventController::class, 'review'])
        ->name('events.review')
        ->middleware('advisor');

    // Admin Only: Final Approval/Publishing
    Route::patch('/events/{event}/finalize', [EventController::class, 'finalize'])
        ->name('events.finalize')
        ->middleware('admin');


    // --- NOTIFICATIONS ---
    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');


    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';