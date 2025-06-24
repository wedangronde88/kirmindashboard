<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\SafetyCheckController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/unit', function () {
    return view('unit');
})->middleware('auth');
// Show login page
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Handle login
Route::post('/login', [LoginController::class, 'login']);
// Show register page
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Handle register
Route::post('/register', [RegisterController::class, 'register']);
// Logout
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Home redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Your other resource routes...
Route::resource('trucks.service-records', ServiceRecordController::class);
Route::resource('trucks.safety-checks', SafetyCheckController::class);
Route::resource('trucks.reminders', ReminderController::class);
Route::resource('trucks', TruckController::class);
Route::get('trucks/{truck}', [TruckController::class, 'show'])->name('trucks.show');

Route::post('/reminders/{reminder}/renew', [ReminderController::class, 'renew']);

// Profile and settings
Route::get('/profile', function () {
    return view('profile');
});
Route::get('/settings', function () {
    return view('settings');
});