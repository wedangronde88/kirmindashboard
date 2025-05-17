<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\SafetyCheckController;
use App\Http\Controllers\ReminderController;


Route::get('/', function () {
    return view('unit');
});

Route::resource('trucks.service-records', ServiceRecordController::class);
Route::resource('trucks.safety-checks', SafetyCheckController::class);
Route::resource('trucks.reminders', ReminderController::class);

Route::resource('trucks', TruckController::class);
Route::get('trucks/{truck}', [TruckController::class, 'show'])->name('trucks.show');

// Profile Route
Route::get('/profile', function () {
    return view('profile'); // Create a 'profile.blade.php' file in the views folder
});

// Settings Route
Route::get('/settings', function () {
    return view('settings'); // Create a 'settings.blade.php' file in the views folder
});

// Logout Route (Example: Redirect to login or home)
Route::get('/logout', function () {
    return redirect('/'); // Redirect to home or login page
});
