<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Contact Management Routes


Route::get('/contacts', [ContactController::class, 'indexWeb'])->name('contacts.index');
Route::get('/student', [StudentController::class, 'indexWeb'])->name('students.index');

// JWT Login page (frontend only; posts to /api/auth/login)
Route::get('/login', function () {
    return view('login');
})->name('login');

// Breeze auth routes removed
