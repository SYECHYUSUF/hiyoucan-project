<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function indexCheckout()
    {
        $user = Auth::user();
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        
        // Ambil alamat user
        $addresses = $user->addresses()->latest()->get();

        return view('checkout.index', compact('cartItems', 'total', 'addresses'));
    }

    public function processCheckout(Request $request)
    {
        // 1. Validasi Input (Updated)
        $request->validate([
            'selected_address_id' => 'required|exists:user_addresses,id',
            'shipping_service'    => 'required|in:jnt,jne,spx',
            'payment_method'      => 'required|in:cod,qris,transfer',
            
            // Validasi file bukti bayar (Sesuai name di blade: payment_proof_qris & payment_proof_transfer)
            'payment_proof_qris'     => 'required_if:payment_method,qris|image|max:2048',
            'payment_proof_transfer' => 'required_if:payment_method,transfer|image|max:2048',
        ]);

        $user = Auth::user();
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) return redirect()->route('cart.index');

        // 2. Hitung Ongkir Manual (Simulasi)
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $shippingCost = match($request->shipping_service) {
            'jnt' => 18000,
            'jne' => 20000,
            'spx' => 15000,
            default => 0,
        };
        $grandTotal = $subtotal + $shippingCost;

        // 3. Ambil Data Alamat
        $addr = UserAddress::where('user_id', $user->id)->findOrFail($request->selected_address_id);
        
        // Buat Snapshot Alamat (String lengkap agar aman jika user hapus alamat nanti)
        // Perhatikan penggunaan kolom baru: phone_number, address_detail, district
        $snapshot  = "PENERIMA: {$addr->recipient_name} ({$addr->phone_number})\n";
        $snapshot .= "ALAMAT: {$addr->address_detail}, Kec. {$addr->district}\n";
        $snapshot .= "KOTA/PROV: {$addr->city}, {$addr->province} ({$addr->postal_code})\n";
        $snapshot .= "KURIR: " . strtoupper($request->shipping_service);

        // Tambahan Info Pembayaran ke Snapshot
        if ($request->payment_method === 'transfer') {
        $snapshot .= "\n\nPEMBAYARAN: TRANSFER BANK (" . ($request->selected_bank ?? 'MANUAL') . ")";
        } elseif ($request->payment_method === 'qris') {
            $snapshot .= "\n\nPEMBAYARAN: QRIS";
        } else {
            $snapshot .= "\n\nPEMBAYARAN: COD";
        }

        // 4. Proses Upload Bukti Bayar
        $proofPath = null;
        if ($request->hasFile('payment_proof_qris')) {
            $proofPath = $request->file('payment_proof_qris')->store('payment_proofs', 'public');
        } elseif ($request->hasFile('payment_proof_transfer')) {
            $proofPath = $request->file('payment_proof_transfer')->store('payment_proofs', 'public');
        }

        // 5. Simpan ke Database (Transaction)
        DB::transaction(function () use ($user, $cartItems, $grandTotal, $shippingCost, $request, $proofPath, $snapshot, $addr) {
            
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $grandTotal,
                'address' => $addr->city, // Kota tujuan untuk report sederhana
                'shipping_address_snapshot' => $snapshot, // Alamat lengkap text
                'payment_method' => $request->payment_method,
                'payment_proof' => $proofPath,
                'status' => ($request->payment_method === 'cod') ? 'pending' : 'pending_payment',
                'shipping_cost' => $shippingCost,
                'shipping_service' => $request->shipping_service,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'status' => 'pending',
                ]);
                // Kurangi stok produk
                $item->product->decrement('stock', $item->quantity);
            }

            // Kosongkan keranjang
            $user->carts()->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}