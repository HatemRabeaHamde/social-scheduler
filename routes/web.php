<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\DashboardController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Posts
        Route::get('/posts/analytics', [PostController::class, 'analytics'])->name('posts.analytics');
    Route::get('/posts/activity-log', [PostController::class, 'activityLog'])->name('posts.activity-log');

    Route::resource('posts', PostController::class);
    Route::post('/posts/{post}/publish', [PostController::class, 'publish'])
        ->name('posts.publish');
 Route::patch('/posts/{post}/update-status', [PostController::class, 'updateStatus'])->name('posts.update-status');

    // Platforms
 
    Route::resource('platforms', PlatformController::class)->except(['show']);
    Route::patch('/platforms/toggle/{platform}', [PlatformController::class, 'toggle'])->name('platforms.toggle');
 
        
});
 