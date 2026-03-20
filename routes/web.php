<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;

// Route for Advisors to "Review" (updates status to 'approved' by advisor)
Route::patch('/events/{event}/review', [EventController::class, 'review'])->name('events.review')->middleware(['auth', 'advisor']);

// Route for Admins to "Finalize"
Route::patch('/events/{event}/finalize', [EventController::class, 'finalize'])->name('events.finalize')->middleware(['auth', 'admin']);
Route::resource('clubs', ClubController::class)->middleware(['auth', 'admin']);
Route::resource('events', \App\Http\Controllers\EventController::class)->middleware(['auth', 'executive']);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
