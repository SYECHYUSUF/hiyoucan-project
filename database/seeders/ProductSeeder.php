<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan Seller Ada
        $seller = User::firstOrCreate(
            ['email' => 'seller@hiyoucan.com'],
            [
                'name' => 'Official Hiyoucan Store',
                'password' => bcrypt('password'),
                'role' => 'seller',
                'email_verified_at' => now(),
            ]
        );

        // 2. Pastikan Kategori Ada (Ambil ID-nya)
        // Kita pakai firstOrCreate biar tidak error kalau kategori belum ada
        $catSkin = Category::firstOrCreate(['slug' => 'skin-care'], ['name' => 'Skin Care'])->id;
        $catBody = Category::firstOrCreate(['slug' => 'body-care'], ['name' => 'Body Care'])->id;
        $catHair = Category::firstOrCreate(['slug' => 'hair-care'], ['name' => 'Hair Care'])->id;
        $catMakeup = Category::firstOrCreate(['slug' => 'makeup'], ['name' => 'Makeup'])->id;
        $catFragrance = Category::firstOrCreate(['slug' => 'fragrances'], ['name' => 'Fragrances'])->id;

        // 3. Data Dummy (20 Item)
        $products = [
            // SKIN CARE
            [
                'name' => 'Luminous Glow Serum',
                'price' => 250000,
                'category_id' => $catSkin,
                'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Serum pencerah wajah dengan Vitamin C murni untuk kulit berkilau alami.',
            ],
            [
                'name' => 'Hydrating Rose Toner',
                'price' => 180000,
                'category_id' => $catSkin,
                'image' => 'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Toner lembut berbahan dasar air mawar untuk menenangkan dan melembapkan kulit.',
            ],
            [
                'name' => 'Midnight Recovery Oil',
                'price' => 450000,
                'category_id' => $catSkin,
                'image' => 'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Minyak wajah malam hari untuk regenerasi sel kulit saat Anda tidur.',
            ],
            [
                'name' => 'Deep Cleanse Clay Mask',
                'price' => 125000,
                'category_id' => $catSkin,
                'image' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Masker lumpur vulkanik untuk membersihkan pori-pori secara mendalam.',
            ],
            [
                'name' => 'Daily UV Defense SPF 50',
                'price' => 210000,
                'category_id' => $catSkin,
                'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Tabir surya ringan yang melindungi kulit dari sinar UV tanpa rasa lengket.',
            ],
            [
                'name' => 'Anti-Aging Eye Cream',
                'price' => 320000,
                'category_id' => $catSkin,
                'image' => 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Krim mata intensif untuk menyamarkan garis halus dan lingkaran hitam.',
            ],

            // BODY CARE
            [
                'name' => 'Lavender Body Wash',
                'price' => 95000,
                'category_id' => $catBody,
                'image' => 'https://lusetabeauty.com/cdn/shop/products/1lavenderbodywash_1024x1024.jpg?v=1719301798',
                'desc' => 'Sabun mandi cair dengan aroma lavender yang menenangkan pikiran.',
            ],
            [
                'name' => 'Shea Butter Body Lotion',
                'price' => 110000,
                'category_id' => $catBody,
                'image' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Lotion tubuh kaya nutrisi untuk kulit kering dan bersisik.',
            ],
            [
                'name' => 'Coffee Scrub Exfoliator',
                'price' => 85000,
                'category_id' => $catBody,
                'image' => 'https://images.unsplash.com/photo-1567721913486-6585f069b332?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Scrub kopi alami untuk mengangkat sel kulit mati dan menghaluskan kulit.',
            ],
            [
                'name' => 'Herbal Hand Cream',
                'price' => 65000,
                'category_id' => $catBody,
                'image' => 'https://nadisherbalbali.com/public/uploads/all/Xqm8j4B8gqa22ic4iNO5bPjPEvHCrc5jWhlBfXkZ.jpg',
                'desc' => 'Krim tangan lembut dengan ekstrak herbal alami.',
            ],

            // HAIR CARE
            [
                'name' => 'Argan Oil Hair Mask',
                'price' => 175000,
                'category_id' => $catHair,
                'image' => 'https://images.unsplash.com/photo-1526947425960-945c6e72858f?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Masker rambut intensif untuk memperbaiki kerusakan dan rambut bercabang.',
            ],
            [
                'name' => 'Volumizing Shampoo',
                'price' => 130000,
                'category_id' => $catHair,
                'image' => 'https://nadisherbalbali.com/public/uploads/all/Xqm8j4B8gqa22ic4iNO5bPjPEvHCrc5jWhlBfXkZ.jpg',
                'desc' => 'Shampoo untuk menambah volume rambut tipis dan lepek.',
            ],
            [
                'name' => 'Keratin Hair Serum',
                'price' => 190000,
                'category_id' => $catHair,
                'image' => 'https://images.unsplash.com/photo-1620916297397-a4a5402a3c6c?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Serum rambut keratin untuk hasil lembut dan berkilau seperti dari salon.',
            ],

            // MAKEUP
            [
                'name' => 'Velvet Matte Lipstick',
                'price' => 150000,
                'category_id' => $catMakeup,
                'image' => 'https://enviostore.com/media/product/2447/product_image-1704376063.jpeg',
                'desc' => 'Lipstick matte dengan tekstur velvet yang tidak membuat bibir kering.',
            ],
            [
                'name' => 'Natural Blush On',
                'price' => 115000,
                'category_id' => $catMakeup,
                'image' => 'https://id-test-11.slatic.net/p/f9eaf13b082676d074c275528e1f5880.jpg',
                'desc' => 'Perona pipi dengan pigmentasi alami untuk wajah segar merona.',
            ],
            [
                'name' => 'Smooth Liquid Foundation',
                'price' => 220000,
                'category_id' => $catMakeup,
                'image' => 'https://mmc.tirto.id/image/otf/720x0/2023/11/22/header-diajeng-foundation_ratio-16x9.jpg',
                'desc' => 'Foundation cair dengan coverage medium to full yang tahan lama.',
            ],

            // FRAGRANCES
            [
                'name' => 'Ocean Breeze Perfume',
                'price' => 550000,
                'category_id' => $catFragrance,
                'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Parfum dengan aroma laut yang segar dan energik.',
            ],
            [
                'name' => 'Vanilla Musk Eau de Parfum',
                'price' => 600000,
                'category_id' => $catFragrance,
                'image' => 'https://nematperfumes.com/cdn/shop/products/NematVanillaMusck_2048x@2x.jpg?v=1563824038',
                'desc' => 'Keharuman vanilla manis dipadukan dengan musk yang elegan.',
            ],
            [
                'name' => 'Citrus Zest Body Mist',
                'price' => 120000,
                'category_id' => $catFragrance,
                'image' => 'https://images.unsplash.com/photo-1615396899839-c99c121888b0?q=80&w=1000&auto=format&fit=crop',
                'desc' => 'Body mist ringan dengan wangi jeruk yang menyegarkan hari Anda.',
            ],
            [
                'name' => 'Woody Spice Cologne',
                'price' => 480000,
                'category_id' => $catFragrance,
                'image' => 'https://www.spacenk.com/on/demandware.static/-/Library-Sites-spacenk-global/default/dwfae46499/WoodyFragranceRoundup_Landscape.jpg',
                'desc' => 'Wewangian maskulin dengan sentuhan kayu cendana dan rempah.',
            ],
        ];

        foreach ($products as $item) {
            Product::create([
                'seller_id' => $seller->id,
                'category_id' => $item['category_id'],
                'name' => $item['name'],
                'slug' => Str::slug($item['name']) . '-' . Str::random(5),
                'description' => $item['desc'],
                'price' => $item['price'],
                'stock' => rand(10, 100), // Stok acak antara 10-100
                'image' => $item['image'],
                'is_active' => true,
            ]);
        }
    }
}