<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AdminApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NotificationController;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('suggestions/by-score', [SuggestionController::class, 'suggestByScore']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User's application routes
    Route::post('applications', [ApplicationController::class, 'store']);
    Route::get('applications', [ApplicationController::class, 'index']);
    Route::get('applications/{application}', [ApplicationController::class, 'show']);

    // Notification routes
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
});


Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function() {
    // Admin's application management routes
    Route::get('applications', [AdminApplicationController::class, 'index']);
    Route::get('applications/{application}', [AdminApplicationController::class, 'show']);
    Route::patch('applications/{application}/status', [AdminApplicationController::class, 'updateStatus']);
}); 