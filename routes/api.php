<?php

use App\Http\Controllers\Admin\AdminBorrowingController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BorrowingController;
use App\Http\Controllers\API\ReturningController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route for authentication
Route::controller(AuthController::class)->group(function () {
    //route for authentication
    Route::post('/login', 'login');

    //route for logout
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

//Route for middleware sanctum
Route::middleware(['auth:sanctum'])->group(function () {

    //Route for admin
    Route::group([
        'middleware' => 'role:admin',
        'prefix' => 'admin'
    ], function () {

        //resource items
        Route::apiResource('/items', ItemController::class)
        ->except('create', 'edit');

        //resource users
        Route::apiResource('/users', UserController::class)
        ->except('create', 'edit');

        //resource categories
        Route::apiResource('/categories', CategoryController::class)
        ->except('create', 'edit');

        //borrowing feat
        Route::group([
            'prefix' => 'borrowings',
            'controller' => AdminBorrowingController::class
        ], function () {
            Route::get('/', 'allBorrowings');
            Route::post('/{id}/approved', 'approveBorrowing');
            Route::post('/{id}/rejected', 'rejectBorrowing');
            Route::put('/{id}/return', 'markAsReturned');
            Route::delete('/{id}', 'destroy');
        });
    });

    //Route for peminjam
    Route::middleware(['role:peminjam'])->group(function () {
        //see items
        Route::apiResource('/items', ItemController::class)
        ->except('store', 'update', 'destroy');

        Route::group([
            'prefix' => 'borrowings',
            'controller' => BorrowingController::class
        ], function () {
            //request borrowing
            Route::post('/', 'requestBorrowing');

            //see history borrowing
            Route::get('/', 'historyBorrowings');

            Route::post('/{id}/return', 'returnBorrowings');
        });
    });
});
