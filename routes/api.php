<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\ContentRepurposeController;
use App\Http\Controllers\Api\GeneratedPostController;
use App\Http\Controllers\Api\GhostwriterController;
use App\Http\Controllers\Api\ConversationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('campaigns', CampaignController::class);
    Route::post('/content/repurpose', [ContentRepurposeController::class, 'store']);
    Route::get('/posts', [GeneratedPostController::class, 'index']);
    Route::get('/posts/{generatedPost}', [GeneratedPostController::class, 'show']);
    Route::patch('/posts/{generatedPost}/status', [GeneratedPostController::class, 'updateStatus']);
    Route::post('/posts/{post}/chat', [GhostwriterController::class, 'chat']);
});