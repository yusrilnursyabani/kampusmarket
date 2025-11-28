<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Seller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database untuk KampusMarket
     */
    public function run(): void
    {
        // 1. Buat Admin User (untuk login ke /admin)
        User::create([
            'name' => 'Admin KampusMarket',
            'email' => 'admin@kampusmarket.com',
            'password' => Hash::make('password'),
        ]);

        $this->command->info('✓ Admin user created (admin@kampusmarket.com / password)');

        // 2. Buat Kategori
        $categories = [
            ['nama_kategori' => 'Elektronik', 'slug' => 'elektronik', 'deskripsi' => 'Produk elektronik dan gadget', 'is_active' => true],
            ['nama_kategori' => 'Fashion', 'slug' => 'fashion', 'deskripsi' => 'Pakaian dan aksesoris', 'is_active' => true],
            ['nama_kategori' => 'Makanan & Minuman', 'slug' => 'makanan-minuman', 'deskripsi' => 'Makanan, minuman, dan snack', 'is_active' => true],
            ['nama_kategori' => 'Buku & Alat Tulis', 'slug' => 'buku-alat-tulis', 'deskripsi' => 'Buku, alat tulis, dan perlengkapan kampus', 'is_active' => true],
            ['nama_kategori' => 'Olahraga', 'slug' => 'olahraga', 'deskripsi' => 'Perlengkapan olahraga dan fitness', 'is_active' => true],
            ['nama_kategori' => 'Hobi & Koleksi', 'slug' => 'hobi-koleksi', 'deskripsi' => 'Barang hobi dan koleksi', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $this->command->info('✓ 6 Kategori created');

        // 3. Buat Seller (berbagai status)
        $sellers = [
            [
                'nama_toko' => 'Toko Elektronik Kampus',
                'singkatan_toko' => 'elektronik_kampus',
                'deskripsi_toko' => 'Menjual berbagai perangkat elektronik berkualitas',
                'nama_pic' => 'Budi Santoso',
                'email_pic' => 'budi@elektronik.com',
                'no_hp_pic' => '081234567890',
                'password' => Hash::make('password'),
                'provinsi' => 'DKI Jakarta',
                'kota_kabupaten' => 'Jakarta Selatan',
                'alamat_lengkap' => 'Jl. Sudirman No. 123, Jakarta Selatan',
                'rt' => '003',
                'rw' => '001',
                'kelurahan' => 'Karet Semanggi',
                'kecamatan' => 'Setiabudi',
                'nomor_ktp' => '3273011001900000',
                'foto_seller' => null,
                'foto_ktp' => null,
                'status_verifikasi' => 'diterima',
                'is_active' => true,
                'verified_at' => now(),
            ],
            [
                'nama_toko' => 'Fashion Store ITB',
                'singkatan_toko' => 'fashion_itb',
                'deskripsi_toko' => 'Fashion trendy untuk mahasiswa',
                'nama_pic' => 'Siti Nurhaliza',
                'email_pic' => 'siti@fashion.com',
                'no_hp_pic' => '081234567891',
                'password' => Hash::make('password'),
                'provinsi' => 'Jawa Barat',
                'kota_kabupaten' => 'Bandung',
                'alamat_lengkap' => 'Jl. Ganesha No. 10, Bandung',
                'rt' => '001',
                'rw' => '002',
                'kelurahan' => 'Lebak Siliwangi',
                'kecamatan' => 'Coblong',
                'nomor_ktp' => '3273011001900001',
                'foto_seller' => null,
                'foto_ktp' => null,
                'status_verifikasi' => 'diterima',
                'is_active' => true,
                'verified_at' => now(),
            ],
            [
                'nama_toko' => 'Snack Corner UGM',
                'singkatan_toko' => 'snack_ugm',
                'deskripsi_toko' => 'Aneka snack dan makanan ringan',
                'nama_pic' => 'Ahmad Dahlan',
                'email_pic' => 'ahmad@snack.com',
                'no_hp_pic' => '081234567892',
                'password' => Hash::make('password'),
                'provinsi' => 'DI Yogyakarta',
                'kota_kabupaten' => 'Sleman',
                'alamat_lengkap' => 'Jl. Kaliurang KM 5, Sleman',
                'rt' => '007',
                'rw' => '002',
                'kelurahan' => 'Caturtunggal',
                'kecamatan' => 'Depok',
                'nomor_ktp' => '3273011001900002',
                'foto_seller' => null,
                'foto_ktp' => null,
                'status_verifikasi' => 'diterima',
                'is_active' => true,
                'verified_at' => now(),
            ],
            [
                'nama_toko' => 'Toko Buku Unair',
                'singkatan_toko' => 'buku_unair',
                'deskripsi_toko' => 'Penyedia buku dan alat tulis lengkap',
                'nama_pic' => 'Dewi Lestari',
                'email_pic' => 'dewi@buku.com',
                'no_hp_pic' => '081234567893',
                'password' => Hash::make('password'),
                'provinsi' => 'Jawa Timur',
                'kota_kabupaten' => 'Surabaya',
                'alamat_lengkap' => 'Jl. Airlangga No. 45, Surabaya',
                'rt' => '005',
                'rw' => '003',
                'kelurahan' => 'Airlangga',
                'kecamatan' => 'Gubeng',
                'nomor_ktp' => '3273011001900003',
                'foto_seller' => null,
                'foto_ktp' => null,
                'status_verifikasi' => 'diterima',
                'is_active' => true,
                'verified_at' => now(),
            ],
            [
                'nama_toko' => 'Toko Pending Verifikasi',
                'singkatan_toko' => 'pending_store',
                'deskripsi_toko' => 'Toko yang masih menunggu verifikasi',
                'nama_pic' => 'Test Pending',
                'email_pic' => 'pending@test.com',
                'no_hp_pic' => '081234567894',
                'password' => Hash::make('password'),
                'provinsi' => 'Jawa Tengah',
                'kota_kabupaten' => 'Semarang',
                'alamat_lengkap' => 'Jl. Pemuda No. 88, Semarang',
                'rt' => '010',
                'rw' => '004',
                'kelurahan' => 'Sekayu',
                'kecamatan' => 'Semarang Tengah',
                'nomor_ktp' => '3273011001900005',
                'foto_seller' => null,
                'foto_ktp' => null,
                'status_verifikasi' => 'menunggu',
                'is_active' => false,
            ],
        ];

        $createdSellers = [];
        foreach ($sellers as $seller) {
            $createdSellers[] = Seller::create($seller);
        }

        $this->command->info('✓ 5 Sellers created (4 verified, 1 pending)');

        // 4. Buat Produk (hanya untuk seller yang verified)
        $products = [
            // Elektronik
            ['seller_id' => 1, 'category_id' => 1, 'nama_produk' => 'Laptop ASUS ROG', 'harga' => 15000000, 'stok' => 5],
            ['seller_id' => 1, 'category_id' => 1, 'nama_produk' => 'Mouse Logitech G502', 'harga' => 650000, 'stok' => 15],
            ['seller_id' => 1, 'category_id' => 1, 'nama_produk' => 'Keyboard Mechanical RGB', 'harga' => 850000, 'stok' => 1], // Stok rendah
            ['seller_id' => 1, 'category_id' => 1, 'nama_produk' => 'Monitor LG 24 inch', 'harga' => 2500000, 'stok' => 0], // Habis
            
            // Fashion
            ['seller_id' => 2, 'category_id' => 2, 'nama_produk' => 'Kaos Polo Pria', 'harga' => 125000, 'stok' => 50],
            ['seller_id' => 2, 'category_id' => 2, 'nama_produk' => 'Celana Jeans Wanita', 'harga' => 250000, 'stok' => 30],
            ['seller_id' => 2, 'category_id' => 2, 'nama_produk' => 'Jaket Hoodie Unisex', 'harga' => 185000, 'stok' => 25],
            
            // Makanan
            ['seller_id' => 3, 'category_id' => 3, 'nama_produk' => 'Keripik Singkong Original', 'harga' => 25000, 'stok' => 100],
            ['seller_id' => 3, 'category_id' => 3, 'nama_produk' => 'Brownies Coklat Premium', 'harga' => 45000, 'stok' => 40],
            ['seller_id' => 3, 'category_id' => 3, 'nama_produk' => 'Kopi Arabica 100g', 'harga' => 65000, 'stok' => 60],
            
            // Buku
            ['seller_id' => 4, 'category_id' => 4, 'nama_produk' => 'Buku Pemrograman Python', 'harga' => 150000, 'stok' => 20],
            ['seller_id' => 4, 'category_id' => 4, 'nama_produk' => 'Novel Laskar Pelangi', 'harga' => 89000, 'stok' => 15],
            ['seller_id' => 4, 'category_id' => 4, 'nama_produk' => 'Paket Alat Tulis Lengkap', 'harga' => 75000, 'stok' => 35],
        ];

        $createdProducts = [];
        foreach ($products as $product) {
            $product['deskripsi'] = "Deskripsi lengkap untuk {$product['nama_produk']}. Produk berkualitas tinggi dengan harga terjangkau. Cocok untuk mahasiswa dan umum.";
            $product['is_active'] = true;
            $product['views'] = rand(10, 500);
            $createdProducts[] = Product::create($product);
        }

        $this->command->info('✓ 13 Products created');

        // 5. Buat Review untuk beberapa produk
        $reviews = [
            ['product_id' => 1, 'nama_pengunjung' => 'John Doe', 'email' => 'john@example.com', 'no_hp' => '081234111111', 'rating' => 5, 'komentar' => 'Laptop sangat bagus! Performa mantap untuk gaming dan kuliah.', 'provinsi' => 'DKI Jakarta', 'kota' => 'Jakarta Selatan'],
            ['product_id' => 1, 'nama_pengunjung' => 'Jane Smith', 'email' => 'jane@example.com', 'no_hp' => '081234111112', 'rating' => 4, 'komentar' => 'Kualitas oke, cuma agak berat dipindah-pindah.', 'provinsi' => 'Jawa Barat', 'kota' => 'Depok'],
            ['product_id' => 2, 'nama_pengunjung' => 'Bob Wilson', 'email' => 'bob@example.com', 'no_hp' => '081234111113', 'rating' => 5, 'komentar' => 'Mouse gaming terbaik! Sensor akurat dan nyaman.', 'provinsi' => 'Jawa Timur', 'kota' => 'Surabaya'],
            ['product_id' => 5, 'nama_pengunjung' => 'Alice Brown', 'email' => 'alice@example.com', 'no_hp' => '081234111114', 'rating' => 5, 'komentar' => 'Kaos nyaman banget, bahannya adem.', 'provinsi' => 'Jawa Barat', 'kota' => 'Bandung'],
            ['product_id' => 5, 'nama_pengunjung' => 'Charlie Davis', 'email' => 'charlie@example.com', 'no_hp' => '081234111115', 'rating' => 3, 'komentar' => 'Lumayan, tapi warnanya sedikit beda dari foto.', 'provinsi' => 'Banten', 'kota' => 'Tangerang'],
            ['product_id' => 8, 'nama_pengunjung' => 'Diana Prince', 'email' => 'diana@example.com', 'no_hp' => '081234111116', 'rating' => 5, 'komentar' => 'Keripik enak banget! Kriuk dan gurih.', 'provinsi' => 'DI Yogyakarta', 'kota' => 'Sleman'],
            ['product_id' => 9, 'nama_pengunjung' => 'Evan Wright', 'email' => 'evan@example.com', 'no_hp' => '081234111117', 'rating' => 4, 'komentar' => 'Brownies lezat, cocok untuk cemilan.', 'provinsi' => 'Jawa Tengah', 'kota' => 'Semarang'],
            ['product_id' => 11, 'nama_pengunjung' => 'Fiona Green', 'email' => 'fiona@example.com', 'no_hp' => '081234111118', 'rating' => 5, 'komentar' => 'Buku sangat membantu belajar Python!', 'provinsi' => 'Jawa Timur', 'kota' => 'Surabaya'],
            ['product_id' => 11, 'nama_pengunjung' => 'George Hill', 'email' => 'george@example.com', 'no_hp' => '081234111119', 'rating' => 4, 'komentar' => 'Bagus untuk pemula, penjelasan mudah dipahami.', 'provinsi' => 'Jawa Tengah', 'kota' => 'Surakarta'],
            ['product_id' => 12, 'nama_pengunjung' => 'Hannah Lee', 'email' => 'hannah@example.com', 'no_hp' => '081234111120', 'rating' => 5, 'komentar' => 'Novel yang menginspirasi, wajib baca!', 'provinsi' => 'Bali', 'kota' => 'Denpasar'],
        ];

        foreach ($reviews as $review) {
            ProductReview::create([
                'product_id' => $review['product_id'],
                'nama_pengunjung' => $review['nama_pengunjung'],
                'email_pengunjung' => $review['email'],
                'no_hp_pengunjung' => $review['no_hp'],
                'provinsi_pengunjung' => $review['provinsi'],
                'kota_pengunjung' => $review['kota'],
                'rating' => $review['rating'],
                'isi_komentar' => $review['komentar'],
                'is_approved' => true,
                'is_spam' => false,
                'ip_address' => '127.0.0.1',
            ]);
        }

        $this->command->info('✓ 10 Product Reviews created');
        
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('🎉 Seeding completed successfully!');
        $this->command->info('========================================');
        $this->command->info('Admin Panel: /admin');
        $this->command->info('  Email: admin@kampusmarket.com');
        $this->command->info('  Pass:  password');
        $this->command->info('');
        $this->command->info('Seller Panel: /seller');
        $this->command->info('  Email: budi@elektronik.com (atau seller lain)');
        $this->command->info('  Pass:  password');
        $this->command->info('========================================');
    }
}
