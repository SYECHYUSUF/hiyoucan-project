<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\ShopController; // <--- PENTING: Import ShopController
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- ROUTE PUBLIC (Bisa diakses tanpa login) ---
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::view('/about', 'about')->name('about');

Route::middleware(['auth'])->group(function () {
    
    // --- FITUR CUSTOMER (CHECKOUT & ORDER) ---
   Route::get('/checkout', [OrderController::class, 'indexCheckout'])->name('checkout.index');
    Route::post('/checkout/process', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index'); // Halaman list pesanan
    // Route untuk melihat riwayat pesanan (My Orders)
    // Pastikan view 'orders.index' ada
    Route::get('/orders', function() {
        $orders = \Illuminate\Support\Facades\Auth::user()->orders()->with('items.product')->latest()->get();
        return view('orders.index', compact('orders')); 
    })->name('orders.index');

    // --- FITUR CART & WISHLIST (Agar Navbar tidak error) ---
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

    // --- FITUR ALAMAT ---
    Route::post('/address', [UserAddressController::class, 'store'])->name('address.store');
    Route::get('/address/{id}/edit', [UserAddressController::class, 'edit'])->name('address.edit'); // Halaman edit
    Route::put('/address/{id}', [UserAddressController::class, 'update'])->name('address.update'); // Proses update
    Route::delete('/address/{id}', [UserAddressController::class, 'destroy'])->name('address.destroy'); // Proses hapus



    // --- FITUR DASHBOARD SELLER (ADMIN TOKO) ---
    Route::get('/dashboard/seller/orders', [OrderController::class, 'sellerIndex'])->name('seller.orders.index');
    Route::patch('/dashboard/seller/orders/{id}', [OrderController::class, 'updateStatus'])->name('seller.orders.update-status');

    // --- FITUR PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';