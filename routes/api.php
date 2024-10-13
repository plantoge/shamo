<?php

use App\Http\Controllers\api\ProductCategoryController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('transaction', [TransactionController::class, 'all']);
    Route::post('checkout', [TransactionController::class, 'checkout']);
});

Route::get('products', [ProductController::class, 'all']);
Route::get('categories', [ProductCategoryController::class, 'all']);
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);