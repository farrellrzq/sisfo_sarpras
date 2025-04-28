<?php

use App\Http\Controllers\Admin\AdminBorrowingController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReturningController;
use App\Http\Controllers\UserController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('/items', ItemController::class);
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/categories', CategoryController::class);
        Route::get('admin/borrowings', [AdminBorrowingController::class, 'allBorrowings']);
        Route::post('admin/borrowings/{id}/approved', [AdminBorrowingController::class, 'approveBorrowing']);
        Route::put('/borrowings/{id}/return', [AdminBorrowingController::class, 'markAsReturned']);
        Route::delete('/borrowings/{id}', [AdminBorrowingController::class, 'destroy']);
    });

    Route::middleware(['role:peminjam'])->group(function () {
        Route::apiResource('/items', ItemController::class)->except('store','update', 'destroy');
        Route::post('/borrowings', [BorrowingController::class, 'requestBorrowing']);
        Route::get('/borrowings', [BorrowingController::class, 'historyBorrowings']);
        Route::post('/returnings/{id}/return', [ReturningController::class, 'returnItem']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});


