<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
});

Route::get('/', function () {
    return view('index');
});