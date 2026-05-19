# 🛒 KampusMarket

**KampusMarket** adalah aplikasi marketplace katalog produk berbasis web yang dibangun dengan Laravel dan Filament. Sistem ini memungkinkan penjual (seller) untuk mengelola produk mereka, sementara pengunjung dapat melihat katalog dan memberikan review tanpa perlu login.

## 📋 Fitur Utama

### 🔐 Admin Panel (`/admin`)
- **Dashboard Analytics**: Statistik lengkap (total seller, produk, review)
- **Manajemen Kategori**: CRUD kategori produk dengan icon
- **Verifikasi Seller**: Approve/reject pendaftaran seller dengan notifikasi email
- **Manajemen Produk**: CRUD produk dari semua seller
- **Moderasi Review**: Approve/reject/spam komentar & rating
- **Charts**: Produk per kategori, toko per provinsi

### 🏪 Seller Panel (`/seller`)
- **Dashboard Seller**: Statistik produk & performa toko
- **Manajemen Produk**: CRUD produk sendiri (auto-filter)
- **Charts**: Stok produk, rating produk
- **Multi-guard Authentication**: Sistem login terpisah dari admin

### 🌐 Frontend Publik (`/`)
- **Katalog Produk**: Grid view dengan pagination
- **Filter & Search**: Berdasarkan kategori, seller, provinsi, kota, harga
- **Detail Produk**: Informasi lengkap, galeri, rating summary
- **Review System**: Pengunjung bisa submit komentar & rating (1-5 bintang)
- **Responsive Design**: Tailwind CSS mobile-friendly

## 🛠 Tech Stack

- **Framework**: Laravel 12.x
- **Admin Panel**: Filament v3
- **Database**: MySQL
- **Frontend**: Blade Templates + Tailwind CSS
- **Icons**: Heroicons, Font Awesome
- **PHP**: 8.3+

## 📦 Instalasi

### Prasyarat
- PHP >= 8.3
- Composer
- MySQL
- Node.js & NPM (untuk asset compilation)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd kampusmarket
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database**
   
   Edit file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kampusmarket
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Migrasi & Seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
   
   > 📝 **Catatan**: Seeder akan membuat data dummy lengkap:
   > - 1 Admin user
   > - 6 Kategori produk
   > - 5 Seller (4 verified, 1 pending)
   > - 13 Produk dengan berbagai stok
   > - 10 Review produk
   >
   > **Lihat file `DATABASE_SETUP.md` untuk detail lengkap!**

6. **Buat Storage Symlink (WAJIB untuk gambar produk)**
   ```bash
   php artisan storage:link
   ```
   > Catatan: file upload gambar disimpan di `storage/app/public` dan umumnya **tidak ikut ter-commit ke Git**.
   > Jadi kalau baru clone/pull, gambar produk yang pernah kamu upload di laptopmu tidak otomatis ada di laptop teman.

7. **Build Assets**
   ```bash
   npm run build
   # atau untuk development:
   npm run dev
   ```

8. **Jalankan Server**
   ```bash
   php artisan serve
   ```

9. **Akses Aplikasi**
   - Frontend: `http://localhost:8000`
   - Admin Panel: `http://localhost:8000/admin`
   - Seller Panel: `http://localhost:8000/seller`

## 🔄 Update Setelah Pull (Tanpa Ribet)

Kalau habis `git pull` dan ingin memastikan dependency/migrasi/storage link/cache sudah beres, jalankan satu perintah:

```bash
composer run sync
```

Atau di Windows kamu bisa jalankan helper script PowerShell:

```powershell
./scripts/sync.ps1
```

> Catatan: ini otomatis mengurus `composer install`, `npm install`, `php artisan migrate`, `php artisan storage:link`, dan clear cache.

## 🖼️ Catatan Tentang Gambar Produk

- Upload gambar sudah otomatis terlihat **di instance aplikasi yang sama**.
- Kalau kamu dan temanmu jalanin aplikasi di laptop masing-masing, file upload (di `storage/app/public`) **tidak ikut Git**, jadi gambar tidak otomatis ikut tersinkron.
- Supaya gambar bisa otomatis terlihat lintas laptop, kalian perlu **storage bersama** (contoh: S3/MinIO) atau pakai satu environment dev yang sama (mis. satu server).

## 🔑 Default Credentials

### Admin
- **Email**: admin@kampusmarket.com
- **Password**: password

### Seller (Verified)
- **Email**: budi@elektronik.com (atau seller lain yang verified)
- **Password**: password

> ⚠️ **PENTING**: Ubah semua password default setelah instalasi!

## 📊 Struktur Database

### Tabel Utama
- `users` - Admin users
- `sellers` - Penjual/toko (authenticatable)
- `categories` - Kategori produk
- `products` - Produk dengan relasi ke seller & category
- `product_reviews` - Review dari pengunjung (tanpa akun)

### Relasi
- `Seller` → `hasMany` → `Product`
- `Category` → `hasMany` → `Product`
- `Product` → `belongsTo` → `Seller`, `Category`
- `Product` → `hasMany` → `ProductReview`

## 📧 Email Notifications

Sistem mengirim email otomatis untuk:
- ✅ **Seller Approved**: Notifikasi seller diterima dengan link login
- ❌ **Seller Rejected**: Notifikasi seller ditolak dengan alasan
- 💬 **Review Thank You**: Ucapan terima kasih setelah submit review

Konfigurasi email di `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS="hello@kampusmarket.com"
MAIL_FROM_NAME="KampusMarket"
```

## 🗂 Struktur Project

```
kampusmarket/
├── app/
│   ├── Filament/
│   │   ├── Resources/          # Admin Resources
│   │   ├── Widgets/            # Admin Widgets
│   │   └── Seller/
│   │       ├── Resources/      # Seller Resources
│   │       ├── Widgets/        # Seller Widgets
│   │       └── Pages/Auth/     # Custom Login
│   ├── Http/Controllers/
│   │   └── ProductController.php
│   ├── Mail/                   # Mailable classes
│   └── Models/                 # Eloquent Models
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── products/
│   │   └── emails/
│   └── css/
└── routes/
    └── web.php
```

## 🚀 Development

### Menjalankan Tests
```bash
php artisan test
```

### Clear Cache
```bash
php artisan optimize:clear
```

### Troubleshooting Gambar Produk Tidak Muncul
- Pastikan `public/storage` sudah ada: jalankan `php artisan storage:link`.
- Pastikan file gambarnya benar-benar ada di `storage/app/public/...`.
- Pastikan `.env` teman punya `APP_URL` yang sesuai dengan domain/vhost yang dipakai (lalu jalankan `php artisan optimize:clear`).

### Generate Filament Resources
```bash
php artisan make:filament-resource ProductName
```

## 📝 SRS (Software Requirements Specification)

Project ini dibuat berdasarkan SRS dengan 9 requirement fungsional:
1. ✅ Manajemen Kategori Produk
2. ✅ Registrasi & Verifikasi Seller
3. ✅ Manajemen Produk Seller
4. ✅ Katalog Produk Publik
5. ✅ Filter & Pencarian Produk
6. ✅ Komentar & Rating (tanpa login)
7. ✅ Moderasi Review Admin
8. ✅ Email Notifikasi
9. ⚠️ Export PDF (optional - belum diimplementasi)

## 🤝 Contributing

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Project ini dibuat untuk keperluan tugas kuliah Proyek Perangkat Lunak.

## 👥 Team

- Developer: [Your Name]
- Mata Kuliah: Proyek Perangkat Lunak
- Institusi: [Your University]

## 📞 Support

Jika ada pertanyaan atau issue, silakan buat issue di GitHub atau hubungi tim developer.

---

**Made with ❤️ for Campus Market**
