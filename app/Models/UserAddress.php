<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    // Sesuaikan kolom ini dengan database/migration Anda
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone_number',   // Ubah dari 'phone'
        'province',       // Ubah dari 'state'
        'city',
        'district',       // Tambahkan kolom ini
        'postal_code',
        'address_detail', // Ubah dari 'address_line'
        'is_primary'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}