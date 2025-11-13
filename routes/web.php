<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return redirect()->route('products.index');
});
Route::resource('products', ProductController::class);

use App\Http\Controllers\CheckoutController;

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Halaman katalog frontend
Route::get('/', [ProductController::class, 'frontend'])->name('frontend.products');
Route::get('/checkout/create/{product_id}', [CheckoutController::class, 'create'])->name('checkout.create');

use App\Http\Controllers\OrderController;

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::patch('/orders/{order}/toggle-paid', [OrderController::class, 'togglePaid'])->name('orders.togglePaid');
Route::patch('/orders/{order}/toggle-received', [OrderController::class, 'toggleReceived'])->name('orders.toggleReceived');
