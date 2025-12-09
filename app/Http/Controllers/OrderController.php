<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $orders = $user->orders()->with('items.product')->latest()->get();

        return view('orders.index', compact('orders'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:500',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        // --- PERBAIKAN: CEK STOK SEBELUM TRANSAKSI ---
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->back()->with('error', 'Stok produk "' . $item->product->name . '" tidak mencukupi. Sisa stok hanya: ' . $item->product->stock);
            }
        }
        // ---------------------------------------------

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        DB::transaction(function () use ($user, $cartItems, $total, $request) {
            // ... (Kode transaksi sama seperti sebelumnya) ...
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'address' => $request->address, 
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'status' => 'pending',
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            $user->carts()->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
}