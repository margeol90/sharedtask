<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

// Protected routes require token
Route::middleware('auth:sanctum')->get('/me', \App\Http\Controllers\Auth\MeController::class);
Route::middleware('auth:sanctum')->post('/logout', LogoutController::class);



