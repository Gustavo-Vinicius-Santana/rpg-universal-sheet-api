<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\FirebaseAuthMiddleware;
use App\Http\Controllers\SheetController;

Route::get('/user', [UserController::class, 'index'])->middleware(FirebaseAuthMiddleware::class);
Route::put('/user', [UserController::class, 'update'])->middleware(FirebaseAuthMiddleware::class);
Route::delete('/user', [UserController::class, 'delete'])->middleware(FirebaseAuthMiddleware::class);

Route::post('/register', [FirebaseAuthController::class, 'register']);
Route::post('/login', [FirebaseAuthController::class, 'login']);

Route::post('/change-password', [FirebaseAuthController::class, 'changePassword']);
Route::post('/change-email', [FirebaseAuthController::class, 'changeEmail'])->middleware(FirebaseAuthMiddleware::class);

Route::get('/user-sheets', [SheetController::class, 'getUserSheets'])->middleware(FirebaseAuthMiddleware::class);
Route::get('/specific-sheet', [SheetController::class, 'getSheet'])->middleware(FirebaseAuthMiddleware::class);

Route::post('/create-sheet', [SheetController::class, 'create'])->middleware(FirebaseAuthMiddleware::class);
Route::put('/update-sheet', [SheetController::class, 'update'])->middleware(FirebaseAuthMiddleware::class);
Route::delete('/delete-sheet', [SheetController::class, 'delete'])->middleware(FirebaseAuthMiddleware::class);