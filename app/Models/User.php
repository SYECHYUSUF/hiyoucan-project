<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // 1. User punya banyak Keranjang
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    // 2. User punya banyak Pesanan
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // 3. User (Seller) punya satu Toko
    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    // 4. User punya banyak Wishlist (INI YANG KURANG)
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function addresses()
{
    return $this->hasMany(UserAddress::class);
}
}