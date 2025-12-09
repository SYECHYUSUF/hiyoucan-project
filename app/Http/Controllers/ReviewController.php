<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $user = Auth::user();

        $hasBought = OrderItem::whereHas('order', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('product_id', $product->id)
            ->where('status', 'completed') // Cek status di item level
            ->exists();

        if (!$hasBought) {
            return back()->with('error', 'Anda harus membeli dan menerima produk ini sebelum memberikan ulasan.');
        }

        // 2. Cek apakah user sudah pernah review sebelumnya (biar tidak spam)
        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        // 3. Simpan Review
        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}