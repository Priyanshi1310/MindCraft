<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;

Route::post('register', [AuthController::class, 'register']);
//Route::post('login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('posts', PostController::class);
   // Route::apiResource('posts/{post}/comments',CommentController::class);
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::get('search', [PostController::class, 'search']);
    Route::apiResource('users', UserController::class);
});
