<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\StatsController;

// Group the routes under an API version (v1)
Route::prefix('v1')->group(function () {
    
    // Authentication routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('books', [BookController::class, 'search']);
    Route::get('/books/{id}/rental-history', [RentalController::class, 'rentalHistoryForBook']);
    Route::get('/books/stats', [StatsController::class, 'getStats']);

    // Book-related routes (authenticated)
    Route::middleware('auth:api')->group(function () {
        Route::get('user', [AuthController::class, 'getUser']);
       
        Route::post('books/rent', [RentalController::class, 'rent']);
        Route::post('books/return', [RentalController::class, 'returnBook']);
        
        // Rental history and statistics
        Route::get('rentals/history', [RentalController::class, 'rentalHistory']);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});