<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- ROUTE PUBLIC ---
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::view('/about', 'about')->name('about');

Route::middleware(['auth'])->group(function () {
    
    // Fitur Customer
    Route::get('/checkout', [OrderController::class, 'indexCheckout'])->name('checkout.index');
    Route::post('/checkout/process', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index'); // Gunakan ini saja

    // Fitur Cart & Wishlist
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');

    // Fitur Alamat
    Route::post('/address', [UserAddressController::class, 'store'])->name('address.store');
    Route::get('/address/{id}/edit', [UserAddressController::class, 'edit'])->name('address.edit');
    Route::put('/address/{id}', [UserAddressController::class, 'update'])->name('address.update');
    Route::delete('/address/{id}', [UserAddressController::class, 'destroy'])->name('address.destroy');

    // --- FITUR DASHBOARD SELLER ---
    // Tambahkan middleware 'seller' agar aman
    Route::middleware(['seller'])->group(function () {
        // PENTING: Tambahkan rute ini untuk dashboard utama seller!
        Route::get('/dashboard/seller', [OrderController::class, 'sellerIndex'])->name('seller.home');
        
        Route::get('/dashboard/seller/orders', [OrderController::class, 'sellerIndex'])->name('seller.orders.index');
        Route::patch('/dashboard/seller/orders/{id}', [OrderController::class, 'updateStatus'])->name('seller.orders.update-status');
    });

    // Fitur Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';