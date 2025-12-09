<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tampilkan isi keranjang
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $cartItems = $user->carts()->with('product')->get();

        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $stock = Product::findOrFail($request['product_id'])->stock;
    
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $request->product_id)
                        ->first();

        $message = 'Product added to cart!';

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            if ($cartItem->quantity > $stock) { 
                $cartItem->quantity =  $stock; 
                $message = $message . ' Stock adjusted!';
            }
            
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('cart.index')->with('success', $message);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::with('product')->findOrFail($id); // Load relasi product

        // --- PERBAIKAN: CEK STOK ---
        if ($request->quantity > $cart->product->stock) {
            return back()->with('error', 'Maaf, stok tidak mencukupi. Maksimal: ' . $cart->product->stock);
        }
        // ---------------------------

        // Cek kepemilikan
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return back()->with('success', 'Cart updated!');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cart->delete();
        return back()->with('success', 'Item removed.');
    }
}