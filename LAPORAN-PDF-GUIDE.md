# Laporan PDF KampusMarket - SRS-09 hingga SRS-14

## Laporan Platform (Admin) - Memerlukan Login Admin

### SRS-09: Laporan Daftar Akun Penjual Berdasarkan Status
**URL:** `/admin/reports/seller-accounts`  
**Format:** PDF Portrait  
**Kolom:** No, Nama User, Nama PIC, Nama Toko, Status (Aktif/Tidak Aktif)  
**Urutan:** Status aktif dulu, kemudian tidak aktif

### SRS-10: Laporan Daftar Toko Berdasarkan Lokasi Propinsi
**URL:** `/admin/reports/stores-by-province`  
**Format:** PDF Portrait  
**Kolom:** No, Nama Toko, Nama PIC, Propinsi  
**Urutan:** Berdasarkan propinsi (A-Z)

### SRS-11: Laporan Daftar Produk Berdasarkan Rating
**URL:** `/admin/reports/product-ratings`  
**Format:** PDF Landscape  
**Kolom:** No, Produk, Kategori, Harga, Rating, Nama Toko, Propinsi  
**Urutan:** Rating tertinggi ke terendah

---

## Laporan Penjual (Seller) - Memerlukan Login Seller

### SRS-12: Laporan Stock Produk (Berdasarkan Stock)
**URL:** `/seller/reports/stock`  
**Format:** PDF Landscape  
**Kolom:** No, Produk, Kategori, Harga, Rating, Stock  
**Urutan:** Stock terbanyak ke tersedikit (menurun)  
**Filter:** Hanya produk milik seller yang login  
**File:** `SRS-MartPlace-12_Laporan-Stock-YYYYMMDD.pdf`

### SRS-13: Laporan Stock Produk (Berdasarkan Rating)
**URL:** `/seller/reports/rating`  
**Format:** PDF Landscape  
**Kolom:** No, Produk, Kategori, Harga, Stock, Rating  
**Urutan:** Rating tertinggi ke terendah (menurun)  
**Filter:** Hanya produk milik seller yang login  
**File:** `SRS-MartPlace-13_Laporan-Rating-YYYYMMDD.pdf`

### SRS-14: Laporan Stock Produk yang Harus Segera Dipesan
**URL:** `/seller/reports/low-stock`  
**Format:** PDF Landscape  
**Kolom:** No, Produk, Kategori, Harga, Stock  
**Kondisi:** Stock < 2  
**Urutan:** Berdasarkan kategori dan produk (alfabetis)  
**Filter:** Hanya produk milik seller yang login  
**File:** `SRS-MartPlace-14_Laporan-Low-Stock-YYYYMMDD.pdf`

---

## Cara Mengakses

### Untuk Admin:
1. Login ke admin panel Filament
2. Akses URL report langsung atau tambahkan menu di Filament panel
3. Contoh: `http://kampusmarket.test/admin/reports/seller-accounts`

### Untuk Seller:
1. Login ke seller panel Filament
2. Akses URL report langsung atau tambahkan menu di Filament panel
3. Contoh: `http://kampusmarket.test/seller/reports/stock`

---

## File yang Dibuat/Dimodifikasi

### Controllers:
- `app/Http/Controllers/AdminReportController.php` (Updated)
- `app/Http/Controllers/SellerReportController.php` (New)

### Views:
- `resources/views/reports/admin/seller-accounts.blade.php` (Updated)
- `resources/views/reports/admin/stores-by-province.blade.php` (Updated)
- `resources/views/reports/admin/product-ratings.blade.php` (Updated)
- `resources/views/reports/seller/srs-12-stock.blade.php` (New)
- `resources/views/reports/seller/srs-13-rating.blade.php` (New)
- `resources/views/reports/seller/srs-14-lowstock.blade.php` (New)

### Routes:
- `routes/web.php` (Updated - added seller report routes)

---

## Nama File PDF yang Dihasilkan

- SRS-09: `SRS-MartPlace-09_Laporan-Akun-Penjual-YYYYMMDD.pdf`
- SRS-10: `SRS-MartPlace-10_Laporan-Toko-Per-Provinsi-YYYYMMDD.pdf`
- SRS-11: `SRS-MartPlace-11_Laporan-Produk-Rating-YYYYMMDD.pdf`
- SRS-12: `SRS-MartPlace-12_Laporan-Stock-YYYYMMDD.pdf`
- SRS-13: `SRS-MartPlace-13_Laporan-Rating-YYYYMMDD.pdf`
- SRS-14: `SRS-MartPlace-14_Laporan-Low-Stock-YYYYMMDD.pdf`
