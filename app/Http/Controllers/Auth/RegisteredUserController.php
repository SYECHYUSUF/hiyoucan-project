<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:buyer,seller'], // Pastikan role valid
        ]);

        // 2. Tentukan Status Akun
        // Jika Seller -> email_verified_at = null (Pending)
        // Jika Buyer -> email_verified_at = now() (Langsung Aktif)
        $emailVerifiedAt = ($request->role === 'buyer') ? now() : null;

        // 3. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => $emailVerifiedAt,
        ]);

        event(new Registered($user));

        // 4. Login User Otomatis
        Auth::login($user);

        // 5. Redirect Sesuai Role
        // Jika Seller, arahkan ke halaman "Pending"
        if ($user->role === 'seller') {
            return redirect()->route('seller.pending');
        }

        // Jika Buyer, arahkan ke Dashboard/Home
        return redirect(route('shop.index', absolute: false));
    }
}