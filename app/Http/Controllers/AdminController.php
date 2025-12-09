<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem; // <--- Tambahkan Import Ini
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // <--- Tambahkan Import Ini

class AdminController extends Controller
{
    public function index()
    {
        // PERBAIKAN: Menghitung Total Sales dari OrderItem yang statusnya 'completed'
        // Bukan dari tabel Order lagi karena status sudah pindah.
        $totalSales = OrderItem::where('status', 'completed')
            ->sum(DB::raw('price * quantity'));

        $stats = [
            'total_sales' => $totalSales,
            'total_orders' => Order::count(),
            'total_users' => User::where('role', 'user')->count(),
            'pending_sellers' => User::where('role', 'seller')->whereNull('email_verified_at')->count(),
        ];

        return view('dashboard.admin.home', compact('stats'));
    }

    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        // Logic Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        // Logic Filter Role
        if ($request->role && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        return view('dashboard.admin.users', compact('users'));
    }

    public function verifySeller(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'seller') {
            return back()->with('error', 'User ini bukan seller.');
        }

        if ($request->action === 'approve') {
            $user->email_verified_at = now();
            $user->save();
            return back()->with('success', 'Seller berhasil disetujui (Approved).');
        } 
        
        if ($request->action === 'reject') {
            $user->email_verified_at = null;
            $user->save();
            return back()->with('success', 'Seller ditolak (Rejected).');
        }

        return back();
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,seller'
        ]);

        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat mengubah role sesama Admin.');
        }

        $user->role = $request->role;

        // Logika verifikasi ulang jika jadi seller
        if ($request->role === 'seller' && !$user->email_verified_at) {
            $user->email_verified_at = null; 
        } elseif ($request->role === 'user') {
            $user->email_verified_at = now();
        }

        $user->save();

        return back()->with('success', 'Role pengguna berhasil diubah menjadi ' . ucfirst($request->role));
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak bisa menghapus Admin.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // --- ADMIN PRODUCT MANAGEMENT ---
    
    public function products(Request $request)
    {
        $query = Product::with(['seller', 'category']);

        // Logic Search Produk
        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        return view('dashboard.admin.products', compact('products'));
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && !Str::startsWith($product->getRawOriginal('image'), 'http')) {
            Storage::disk('public')->delete($product->getRawOriginal('image'));
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus karena melanggar ketentuan.');
    }

    // --- KATEGORI ---
    
    public function categories()
    {
        $categories = Category::withCount('products')->get();
        return view('dashboard.admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        Category::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('dashboard.admin.categories-edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        try {
            $category->delete();
            return back()->with('success', 'Kategori berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1451) {
                return back()->with('error', 'Gagal menghapus: Kategori ini masih digunakan oleh satu atau lebih Produk.');
            }
            
            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus kategori.');
        }
    }

    // --- EDIT USER INFO ---

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.admin.users-edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8', 
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Informasi pengguna berhasil diperbarui.');
    }
}