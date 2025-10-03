<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes (public)
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Bảo vệ toàn bộ API dưới đây bằng JWT và kiểm tra isActive
Route::middleware(['auth:api', 'check.user.active'])->group(function () {    // Authenticated user/session routes
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);

    // Contact routes
    Route::get('contact/search', [ContactController::class, 'search']);
    Route::get('contact/stats', [ContactController::class, 'stats']);
    Route::post('contact/{contact}/email', [ContactController::class, 'sendEmail']);
    Route::apiResource('contact', ContactController::class);

    // Student routes
    Route::get('student/search', [StudentController::class, 'search']);
    Route::get('student/stats', [StudentController::class, 'stats']);
    Route::apiResource('student', StudentController::class);
});
