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
    /**
     * Menampilkan riwayat pesanan untuk Customer
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan halaman checkout
     */
    public function indexCheckout()
    {
        $user = Auth::user();
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $addresses = $user->addresses()->latest()->get();

        return view('checkout', compact('cartItems', 'total', 'addresses'));
    }

    /**
     * Memproses pesanan (Checkout)
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'selected_address_id' => 'required|exists:user_addresses,id',
            'shipping_service'    => 'required|in:jnt,jne,spx',
            'payment_method'      => 'required|in:cod,qris,transfer',
            'payment_proof_qris'     => 'required_if:payment_method,qris|image|max:2048',
            'payment_proof_transfer' => 'required_if:payment_method,transfer|image|max:2048',
        ]);

        $user = Auth::user();
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) return redirect()->route('cart.index');

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $shippingCost = match($request->shipping_service) {
            'jnt' => 18000,
            'jne' => 20000,
            'spx' => 15000,
            default => 0,
        };
        $grandTotal = $subtotal + $shippingCost;

        $addr = UserAddress::where('user_id', $user->id)->findOrFail($request->selected_address_id);
        
        $snapshot  = "PENERIMA: {$addr->recipient_name} ({$addr->phone_number})\n";
        $snapshot .= "ALAMAT: {$addr->address_detail}, Kec. {$addr->district}\n";
        $snapshot .= "KOTA/PROV: {$addr->city}, {$addr->province} ({$addr->postal_code})\n";
        $snapshot .= "KURIR: " . strtoupper($request->shipping_service);

        $proofPath = null;
        if ($request->hasFile('payment_proof_qris')) {
            $proofPath = $request->file('payment_proof_qris')->store('payment_proofs', 'public');
        } elseif ($request->hasFile('payment_proof_transfer')) {
            $proofPath = $request->file('payment_proof_transfer')->store('payment_proofs', 'public');
        }

        DB::transaction(function () use ($user, $cartItems, $grandTotal, $shippingCost, $request, $proofPath, $snapshot, $addr) {
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $grandTotal,
                'address' => $addr->city,
                'shipping_address_snapshot' => $snapshot,
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
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            $user->carts()->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * Dashboard Seller - Menampilkan daftar pesanan masuk
     */
   // app/Http/Controllers/OrderController.php

public function sellerIndex()
{
    // 1. Ambil semua data order untuk tabel
    $orders = Order::with(['user', 'items.product'])->latest()->get();

    // 2. Hitung statistik untuk dikirim ke view
    $stats = [
        // Total pendapatan dari order yang bukan 'cancelled'
        'revenue' => Order::where('status', '!=', 'cancelled')->sum('total_price'),
        
        // Total item yang terjual (sum quantity dari tabel order_items)
        'items_sold' => \App\Models\OrderItem::sum('quantity'),
        
        // Total produk aktif di toko
        'total_products' => \App\Models\Product::count(),
    ];

    // 3. Kirimkan kedua variabel (orders dan stats) ke view
    return view('dashboard.seller.home', compact('orders', 'stats'));
}

    /**
     * Dashboard Seller - Memperbarui status pesanan
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}