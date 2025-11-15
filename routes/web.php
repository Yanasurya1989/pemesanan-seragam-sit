<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

Route::get('/', [ProductController::class, 'frontend'])->name('frontend.products');

// Resource untuk CRUD produk
Route::resource('products', ProductController::class);

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/create/{product_id}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Orders routes
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::patch('/orders/{order}/toggle-paid', [OrderController::class, 'togglePaid'])->name('orders.togglePaid');
Route::patch('/orders/{order}/toggle-received', [OrderController::class, 'toggleReceived'])->name('orders.toggleReceived');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

// Route::get('/produk', [ProductController::class, 'frontend'])->name('frontend.products');
