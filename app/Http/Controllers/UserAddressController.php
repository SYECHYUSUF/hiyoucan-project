<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    // ... index() boleh tetap sama ...

    /**
     * Menyimpan alamat baru (Update Validasi agar sesuai Form Checkout)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'recipient_name' => 'required|string|max:100',
            'phone_number'   => 'required|string|max:20', // Sesuaikan name="phone_number"
            'province'       => 'required|string|max:100',
            'city'           => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'postal_code'    => 'required|string|max:10',
            'address_detail' => 'required|string|max:255', // Sesuaikan name="address_detail"
        ]);

        $validatedData['user_id'] = Auth::id();
        
        // Cek jika ini alamat pertama, jadikan primary
        $validatedData['is_primary'] = UserAddress::where('user_id', Auth::id())->doesntExist();

        UserAddress::create($validatedData);

        // Redirect kembali ke halaman checkout setelah simpan
        return redirect()->back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    /**
     * Menampilkan Form Edit (PENTING: Tambahkan ini)
     */
    public function edit($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        // Pastikan kamu punya view 'user.address.edit' atau sesuaikan
        return view('user.address.edit', compact('address'));
    }

    /**
     * Update alamat (Update Validasi)
     */
    public function update(Request $request, $id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);

        $validatedData = $request->validate([
            'recipient_name' => 'sometimes|required|string|max:100',
            'phone_number'   => 'sometimes|required|string|max:20',
            'province'       => 'sometimes|required|string|max:100',
            'city'           => 'sometimes|required|string|max:100',
            'district'       => 'sometimes|required|string|max:100',
            'postal_code'    => 'sometimes|required|string|max:10',
            'address_detail' => 'sometimes|required|string|max:255',
        ]);

        $address->update($validatedData);

        return redirect()->route('checkout.index')->with('success', 'Alamat berhasil diperbarui!');
    }

    /**
     * Hapus alamat
     */
    public function destroy($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Alamat berhasil dihapus.');
    }
}