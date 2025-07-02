<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\SafetyCheckController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TripHistoryController;

// -----------------------------
// Public routes (no auth needed)
// -----------------------------
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// -----------------------------
// Protected routes (require auth)
// -----------------------------
Route::middleware('auth')->group(function () {
    // Dashboard/unit page
    Route::get('/unit', fn() => view('unit'))->name('unit');

    // Truck management (RESTful)
    Route::resource('trucks', TruckController::class);
    Route::get('trucks/{truck}', [TruckController::class, 'show'])->name('trucks.show');

    // Nested resources for trucks
    Route::resource('trucks.service-records', ServiceRecordController::class);
    Route::resource('trucks.safety-checks', SafetyCheckController::class);
    Route::resource('trucks.reminders', ReminderController::class);

    // Reminders: renew
    Route::post('/reminders/{reminder}/renew', [ReminderController::class, 'renew'])->name('reminders.renew');

    // Trip histories (Excel-like table)
    Route::get('trip-histories', [TripHistoryController::class, 'index'])->name('trip-histories.index');
    Route::post('trip-histories/update-multiple', [TripHistoryController::class, 'updateMultiple'])->name('trip-histories.update-multiple');
    Route::get('trip-histories/export', [TripHistoryController::class, 'export'])->name('trip-histories.export');

    // Profile & settings
    Route::view('/profile', 'profile')->name('profile');
    Route::view('/settings', 'settings')->name('settings');
});