<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Cust\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response([
        'message' => 'Api is Working'
    ], 200);
});

Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Routes for all authenticated users
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthenticationController::class, 'logout']);

    // Administrator routes
    Route::middleware(['role:administrator'])->group(function () {
        // Product
        Route::post('/admin/productPost', [ProductController::class, 'productPost']);
        Route::get('/admin/getProduct', [ProductController::class, 'getProduct']);
        // update Product
        Route::post('/admin/updateProduct/{id}', [ProductController::class, 'updateProduct']);

        // Voucher
        Route::post('/admin/voucherPost', [VoucherController::class, 'voucherPost']);
        Route::post('/admin/updateVoucher/{id}', [VoucherController::class, 'updateVoucher']);

        // get Voucher
        Route::get('/admin/getVoucher', [VoucherController::class, 'getVoucher']);
    });

    // Customer routes
    Route::middleware(['role:customer'])->group(function () {
        Route::post('/cust/order', [OrderController::class, 'order']);
    });
});
