# Cara Test Laporan PDF Seller

## ✅ Status Sistem
Semua konfigurasi sudah benar:
- ✓ Routes terdaftar (seller.reports.stock, seller.reports.rating, seller.reports.lowstock)
- ✓ Controller exists dan functional (SellerReportController)
- ✓ PDF views exists (srs-12-stock, srs-13-rating, srs-14-lowstock)
- ✓ Auth guard seller dikonfigurasi dengan benar
- ✓ Seller panel menggunakan guard 'seller'
- ✓ Data seller ada (7 sellers, 4 dengan status 'diterima')

## 🔐 Login sebagai Seller

### Akun Seller yang Tersedia:
1. **Budi Santoso** (4 produk)
   - Email: budi@elektronik.com
   - Password: Masukkan password yang Anda gunakan saat registrasi

2. **Affan Firadus** (2 produk)
   - Email: affan@gmail.com
   - Password: password (atau password yang sudah Anda set)

3. **Hasan** (1 produk)
   - Email: suryadharmahasan@gmail.com
   - Password: password (atau password yang sudah Anda set)

### URL Login Seller:
```
http://localhost:8000/seller/login
```

## 📊 Cara Mengakses Laporan

### Langkah 1: Login ke Seller Panel
1. Buka browser
2. Akses: `http://localhost:8000/seller/login`
3. Login dengan salah satu akun seller di atas
4. Anda akan masuk ke Seller Dashboard

### Langkah 2: Akses Menu Laporan PDF
1. Lihat sidebar kiri
2. Klik menu "**Laporan PDF**" (icon: document-chart-bar)
3. Anda akan melihat 3 kartu laporan:
   - 🔵 **Laporan Stock Produk** (SRS-MartPlace-12)
   - 🟡 **Laporan Rating Produk** (SRS-MartPlace-13)
   - 🔴 **Produk Perlu Dipesan** (SRS-MartPlace-14)

### Langkah 3: Download PDF
1. Klik tombol "**Download PDF**" pada kartu yang diinginkan
2. Browser akan otomatis download file PDF
3. File akan bernama sesuai format:
   - `SRS-MartPlace-12_Laporan-Stock-20251212.pdf`
   - `SRS-MartPlace-13_Laporan-Rating-20251212.pdf`
   - `SRS-MartPlace-14_Laporan-LowStock-20251212.pdf`

## 🔍 Troubleshooting

### Jika muncul error "Unauthenticated" atau redirect ke login:

**Solusi 1: Clear Browser Cache**
```
Ctrl + Shift + Delete
- Clear cookies and site data
- Clear cached images and files
```

**Solusi 2: Clear Laravel Cache**
```powershell
php artisan optimize:clear
```

**Solusi 3: Pastikan Session Driver benar**
Check di `.env`:
```
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

Pastikan tabel `sessions` ada di database:
```powershell
php artisan migrate
```

**Solusi 4: Test dengan Incognito/Private Window**
- Buka browser dalam mode incognito
- Login kembali sebagai seller
- Test download laporan

### Jika PDF tidak ter-download:

**Check 1: Browser Pop-up Blocker**
- Allow pop-ups untuk localhost

**Check 2: DomPDF Package**
```powershell
composer require barryvdh/laravel-dompdf
```

**Check 3: Storage Permissions**
```powershell
# Windows PowerShell
icacls "storage" /grant Everyone:F /t
```

### Jika PDF kosong atau error:

**Check 1: Data Produk**
Pastikan seller memiliki produk:
```powershell
php check_sellers_status.php
```

**Check 2: View Errors**
Check log:
```powershell
Get-Content storage\logs\laravel.log -Tail 50
```

## 🧪 Testing Manual via URL

Jika Anda sudah login sebagai seller di panel, coba akses langsung:

```
http://localhost:8000/seller/reports/stock
http://localhost:8000/seller/reports/rating
http://localhost:8000/seller/reports/low-stock
```

Seharusnya langsung download PDF tanpa error.

## ✨ Perubahan yang Sudah Dilakukan

1. ✅ Removed `target="_blank"` dari semua link (untuk preserve session)
2. ✅ Cleared all caches (optimize:clear)
3. ✅ Verified all routes registered correctly
4. ✅ Verified controllers functional
5. ✅ Verified PDF views exist
6. ✅ Verified seller data exists

## 💡 Tips

1. **Gunakan akun Budi Santoso** untuk testing karena memiliki 4 produk (data paling lengkap)
2. **Jangan buka link di tab baru** - klik langsung di halaman yang sama
3. **Pastikan sudah login** sebelum mengakses menu Laporan PDF
4. **Check browser console** (F12) jika ada JavaScript errors

---

**Update:** 12 Desember 2025
**Status:** ✅ Ready for testing
