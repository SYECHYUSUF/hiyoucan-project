<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerIsApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek jika user adalah Seller (Manager)
        if ($user && $user->role === 'seller') {
            // Jika belum diapprove (email_verified_at masih kosong)
            if (is_null($user->email_verified_at)) {
                // Jika dia sedang tidak mengakses halaman pending atau logout, lempar ke halaman pending
                if (!$request->routeIs('seller.pending') && !$request->routeIs('logout') && !$request->routeIs('profile.destroy')) {
                    return redirect()->route('seller.pending');
                }
            }
        }

        return $next($request);
    }
}