<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController as ApiPostController;
use App\Http\Controllers\Api\PlatformController as ApiPlatformController;
use App\Http\Controllers\Api\AuthController ;
 
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // API Post Routes
    Route::apiResource('posts', ApiPostController::class);
    Route::get('posts/filter/{status}', [ApiPostController::class, 'filter']);
    
    // API Platform Routes
    Route::get('platforms', [ApiPlatformController::class, 'index']);
    Route::post('platforms/{platform}/toggle', [ApiPlatformController::class, 'toggle']);
});