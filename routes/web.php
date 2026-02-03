<?php

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\AuthController;

Route::get('/', [ProductController::class, 'frontend'])->name('frontend.products');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/create/{product_id}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// CART ROUTES
Route::get('/cart', [CheckoutController::class, 'cart'])->name('cart.index');
Route::post('/cart/add', [CheckoutController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CheckoutController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/checkout', [CheckoutController::class, 'checkoutCart'])->name('cart.checkout');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    // dashboard admin
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // CRUD produk
    Route::resource('products', ProductController::class);

    // Orders routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/toggle-paid', [OrderController::class, 'togglePaid'])->name('orders.togglePaid');
    Route::patch('/orders/{order}/toggle-received', [OrderController::class, 'toggleReceived'])->name('orders.toggleReceived');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::resource('barang-masuk', BarangMasukController::class);
    Route::get('/orders/kwitansi/{order}', [OrderController::class, 'kwitansi'])
        ->name('orders.kwitansi');

    Route::get('/test-pdf', function () {
        $pdf = Pdf::loadHTML('<h1>Halo PDF!</h1>');
        return $pdf->stream();
    });

    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
});
