# Panduan Login Seller untuk Hasan & Affan

## URL Login Seller
**http://127.0.0.1:8000/seller/login**

JANGAN login di:
- ❌ http://127.0.0.1:8000/login (ini untuk customer biasa)
- ❌ http://127.0.0.1:8000/admin (ini untuk admin)

## Akun yang Terdaftar

### Hasan
- Email: **suryadharmahasan@gmail.com**
- Nama Toko: Hasaan Toko
- Status: ✅ Terverifikasi & Aktif
- Password: (password yang digunakan saat registrasi)

### Affan
- Email: **affan@gmail.com**
- Nama Toko: Toko Madura
- Status: ✅ Terverifikasi & Aktif
- Password: (password yang digunakan saat registrasi)

## Jika Lupa Password

Jalankan command berikut untuk reset password (ganti dengan password baru):

```powershell
# Reset password Hasan
php artisan tinker --execute="\$seller = App\Models\Seller::where('email_pic', 'suryadharmahasan@gmail.com')->first(); \$seller->password = bcrypt('password_baru'); \$seller->save(); echo 'Password Hasan berhasil direset';"

# Reset password Affan
php artisan tinker --execute="\$seller = App\Models\Seller::where('email_pic', 'affan@gmail.com')->first(); \$seller->password = bcrypt('password_baru'); \$seller->save(); echo 'Password Affan berhasil direset';"
```

## Troubleshooting

1. **Pastikan menggunakan URL yang benar** - `/seller/login`
2. **Gunakan email lengkap** - termasuk @gmail.com
3. **Password case-sensitive** - perhatikan huruf besar/kecil
4. **Clear browser cache** - jika ada masalah session
