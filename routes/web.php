<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Halaman About
Route::get('/about', function () {
    return view('about')->with('title', 'About');
})->name('about');

// Halaman Shop & Detail Produk
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Harus Login)
|--------------------------------------------------------------------------
*/

// Dashboard Logic (HomeController akan memfilter Buyer agar tidak masuk sini)
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- BUYER FEATURES ---
    
    // Cart (Keranjang)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update'); // Route Update Quantity
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    // Wishlist (Favorit)
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Order & Checkout
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    // Review Produk
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('reviews.store');


    // --- SELLER PENDING PAGE ---
    Route::get('/seller/pending', function () {
        if (Auth::user()->email_verified_at) {
            return redirect()->route('seller.home');
        }
        return view('auth.pending');
    })->name('seller.pending');


    // --- ADMIN ROUTES ---
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('home');
        
        // Manage Users
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{id}/verify', [AdminController::class, 'verifySeller'])->name('users.verify');
        Route::patch('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.update-role');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // Edit User Info
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        
        // Manage Categories
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{id}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        // Manage Products (Supervision)
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    });


    // --- SELLER ROUTES ---
    Route::middleware(['seller', 'seller.approved'])->prefix('seller')->name('seller.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [StoresController::class, 'index'])->name('home');
        
        // Store Profile
        Route::get('/store/profile', [StoresController::class, 'edit'])->name('store.edit');
        Route::post('/store/profile', [StoresController::class, 'update'])->name('store.update');

        // Orders Management
        Route::get('/orders', [StoresController::class, 'orders'])->name('orders');
        Route::patch('/orders/{id}/status', [StoresController::class, 'updateOrderStatus'])->name('orders.update-status');
        
        // Product CRUD
        Route::resource('products', ProductsController::class);
    });
});

require __DIR__.'/auth.php';