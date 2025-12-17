<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    // Menentukan nama tabel (opsional jika nama tabel jamak, misal: user_addresses)
    protected $table = 'user_addresses';

    // Kolom mana saja yang boleh diisi secara massal (mass assignment)
    // Pastikan kolom ini ada di database-mu nanti
    protected $fillable = [
        'user_id',
        'recipient_name', // Nama penerima paket
        'address_line',   // Alamat jalan lengkap
        'city',           // Kota
        'state',          // Provinsi
        'postal_code',    // Kode Pos
        'phone',          // Nomor Telepon penerima
        'is_primary'      // Penanda alamat utama (boolean: 0 atau 1)
    ];

    /**
     * Relasi ke User
     * Setiap alamat dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}