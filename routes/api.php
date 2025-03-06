<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\FirebaseAuthMiddleware;

Route::get('/user', [UserController::class, 'index'])->middleware(FirebaseAuthMiddleware::class);

Route::post('/register', [FirebaseAuthController::class, 'register']);
Route::post('/login', [FirebaseAuthController::class, 'login']);