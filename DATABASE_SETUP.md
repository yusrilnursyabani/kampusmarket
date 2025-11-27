# 📦 Panduan Setup Database KampusMarket

## 🎯 Untuk Teman yang Clone Repository

Setelah clone repository, ikuti langkah berikut untuk setup database:

---

## 📋 Langkah 1: Setup Environment

1. **Copy file .env.example ke .env**
   ```bash
   cp .env.example .env
   ```

2. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

3. **Edit file .env** untuk konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kampusmarket
   DB_USERNAME=root
   DB_PASSWORD=
   ```

---

## 📋 Langkah 2: Buat Database

Buka MySQL/phpMyAdmin dan jalankan:

```sql
CREATE DATABASE kampusmarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Atau via terminal:
```bash
mysql -u root -e "CREATE DATABASE kampusmarket"
```

---

## 📋 Langkah 3: Migrasi & Seeder

Jalankan perintah berikut untuk membuat tabel dan mengisi data dummy:

```bash
php artisan migrate
php artisan db:seed
```

### ✅ Data yang Akan Dibuat:

**1. Admin User (1 akun)**
- Email: `admin@kampusmarket.com`
- Password: `password`
- Role: Admin panel

**2. Kategori (6 kategori)**
- Elektronik
- Fashion
- Makanan & Minuman
- Buku & Alat Tulis
- Olahraga
- Hobi & Koleksi

**3. Seller (5 toko)**
- **4 Toko Verified:**
  - Toko Elektronik Kampus (`budi@elektronik.com`)
  - Fashion Store ITB (`siti@fashion.com`)
  - Snack Corner UGM (`ahmad@snack.com`)
  - Toko Buku Unair (`dewi@buku.com`)
- **1 Toko Pending:**
  - Toko Pending Verifikasi (`pending@test.com`)

**4. Produk (13 produk)**
- 4 produk elektronik (termasuk stok rendah & habis)
- 3 produk fashion
- 3 produk makanan & minuman
- 3 produk buku & alat tulis

**5. Review (10 review)**
- Rating 3-5 bintang
- Sudah approved

---

## 📋 Langkah 4: Install Dependencies

```bash
composer install
npm install
npm run build
```

---

## 🚀 Langkah 5: Jalankan Server

```bash
php artisan serve
```

Akses aplikasi:
- **Frontend**: http://127.0.0.1:8000
- **Admin Panel**: http://127.0.0.1:8000/admin
- **Seller Panel**: http://127.0.0.1:8000/seller

---

## 🔑 Kredensial Default

### Admin Panel (`/admin`)
- **Email**: admin@kampusmarket.com
- **Password**: password

### Seller Panel (`/seller`)
- **Email**: budi@elektronik.com (atau seller lain yang verified)
- **Password**: password

### Seller Accounts:
| Email | Password | Toko | Status |
|-------|----------|------|--------|
| budi@elektronik.com | password | Toko Elektronik Kampus | ✅ Verified |
| siti@fashion.com | password | Fashion Store ITB | ✅ Verified |
| ahmad@snack.com | password | Snack Corner UGM | ✅ Verified |
| dewi@buku.com | password | Toko Buku Unair | ✅ Verified |
| pending@test.com | password | Toko Pending Verifikasi | ⏳ Pending |

---

## 🔄 Reset Database (Jika Perlu)

Jika ingin reset database dan isi ulang data:

```bash
php artisan migrate:fresh --seed
```

**⚠️ Peringatan**: Perintah ini akan **menghapus semua data** dan membuat ulang dari awal!

---

## 🐛 Troubleshooting

### Error: Access denied for user 'root'@'localhost'
**Solusi**: Edit `.env` dan sesuaikan DB_USERNAME & DB_PASSWORD dengan MySQL Anda

### Error: Database 'kampusmarket' doesn't exist
**Solusi**: Buat database dulu dengan perintah CREATE DATABASE

### Error: Class 'XXX' not found
**Solusi**: Jalankan `composer dump-autoload` dan `php artisan optimize:clear`

### Produk tidak muncul di frontend
**Solusi**: 
1. Cek apakah seeder berhasil: `php artisan db:seed`
2. Clear cache: `php artisan optimize:clear`
3. Cek database: Pastikan ada data di tabel `products`

---

## 📧 Email Configuration (Opsional)

Untuk testing email notifications, edit `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="KampusMarket"
```

Atau gunakan Mailtrap untuk development:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
```

---

## ✅ Verifikasi Setup Berhasil

Setelah setup, pastikan:
- [ ] Admin bisa login ke `/admin`
- [ ] Seller bisa login ke `/seller`
- [ ] Frontend menampilkan 13 produk
- [ ] Filter & search berfungsi
- [ ] Detail produk menampilkan review

---

## 📞 Bantuan

Jika ada masalah, hubungi:
- Repository: https://github.com/yusrilnursyabani/kampusmarket
- Email: yusrilnursabani12@gmail.com

---

**✨ Happy Coding!**
