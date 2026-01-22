<?php

use App\Http\Controllers\{ShoppingListController, ShoppingItemController };
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
    Route::post('/shopping-lists', [ShoppingListController::class, 'store']);
    Route::get('/shopping-list/{shoppingList}', [ShoppingListController::class, 'show']);

    Route::prefix('shopping-lists/{shoppingList}')->group(function () {
        Route::get('items', [ShoppingItemController::class, 'index']);
        Route::post('items', [ShoppingItemController::class, 'store']);
    });

    Route::prefix('shopping-items')->group(function () {
        Route::patch('{item}', [ShoppingItemController::class, 'update']);
        Route::patch('{item}/toggle', [ShoppingItemController::class, 'toggle']);
        Route::delete('{item}', [ShoppingItemController::class, 'destroy']);
    });
});




