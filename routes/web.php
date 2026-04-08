<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [UsersController::class, 'users']);
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::delete('/delete_user/{id}', [UsersController::class, 'deleteUser']);
});

Route::get('/', function () { return view('index'); });
Route::get('/unauthorized', function () { return view('unauthorized'); });
Route::post('/login', [AuthController::class, 'login']);
