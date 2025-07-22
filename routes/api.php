<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\Admin\ApplicationController as AdminApplicationController;

Route::post('suggestions/by-score', [SuggestionController::class, 'suggestByScore']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User's application routes
    Route::post('applications', [ApplicationController::class, 'store']);
    Route::get('applications', [ApplicationController::class, 'index']);
    Route::get('applications/{application}', [ApplicationController::class, 'show']);
});


Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function() {
    // Admin's application management routes
    Route::get('applications', [AdminApplicationController::class, 'index']);
    Route::get('applications/{application}', [AdminApplicationController::class, 'show']);
    Route::patch('applications/{application}/status', [AdminApplicationController::class, 'updateStatus']);
}); 