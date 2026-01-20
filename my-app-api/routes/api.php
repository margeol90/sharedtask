<?php

use App\Http\Controllers\Api\ShoppingListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

// Protected routes require token
Route::middleware('auth:sanctum')->group(function () {
	Route::get('/me', MeController::class);
	Route::post('/logout', LogoutController::class);

    Route::get('/shopping-lists', [ShoppingListController::class, 'index']);
    Route::post('/shopping-list', [ShoppingListController::class, 'store']);
    Route::get('/shopping-list/{id}', [ShoppingListController::class, 'find']);
});




