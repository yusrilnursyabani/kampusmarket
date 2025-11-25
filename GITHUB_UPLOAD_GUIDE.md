# 📘 PANDUAN UPLOAD PROJECT KAMPUSMARKET KE GITHUB

## 📌 PERSIAPAN AWAL

### ✅ Checklist Sebelum Upload:
- [x] File `.gitignore` sudah diupdate
- [x] File `.env.example` sudah dibuat (tanpa APP_KEY)
- [x] File `README.md` sudah dibuat
- [x] Folder `/vendor` dan `/node_modules` akan diabaikan Git
- [x] File `.env` TIDAK akan diupload (sudah ada di .gitignore)

---

## 🔧 LANGKAH 1: MEMBUAT REPOSITORY GITHUB BARU

### Via Website GitHub:

1. **Login ke GitHub**
   - Buka https://github.com
   - Login dengan akun Anda

2. **Buat Repository Baru**
   - Klik tombol **"+"** di pojok kanan atas
   - Pilih **"New repository"**

3. **Isi Form Repository:**
   ```
   Repository name: kampusmarket
   Description: Marketplace Katalog Produk Kampus - Laravel + Filament
   Visibility: ☑ Public (atau Private jika tugas rahasia)
   
   ❌ JANGAN centang:
   - Add a README file
   - Add .gitignore
   - Choose a license
   
   (Kita sudah punya file-file ini di lokal)
   ```

4. **Klik tombol "Create repository"**

5. **Salin URL Repository**
   
   Setelah repository dibuat, akan muncul halaman dengan instruksi. Salin URL repository Anda, contoh:
   ```
   https://github.com/username-anda/kampusmarket.git
   ```

---

## 💻 LANGKAH 2: INISIALISASI GIT DI PROJECT LOKAL

Buka **PowerShell** atau **Terminal** di folder project `C:\laragon\www\kampusmarket`, lalu jalankan perintah berikut **satu per satu**:

### 1️⃣ Inisialisasi Git Repository
```powershell
git init
```
**Output yang diharapkan:**
```
Initialized empty Git repository in C:/laragon/www/kampusmarket/.git/
```

### 2️⃣ Ubah Branch Default ke "main"
```powershell
git branch -M main
```
*Ini mengubah nama branch default dari "master" ke "main" (standar GitHub modern)*

### 3️⃣ Tambahkan Semua File ke Staging
```powershell
git add .
```
**Perintah ini akan menambahkan SEMUA file kecuali yang ada di `.gitignore`**

*Periksa file apa saja yang ditambahkan (opsional):*
```powershell
git status
```

### 4️⃣ Buat Commit Pertama
```powershell
git commit -m "Initial commit - KampusMarket Laravel Project"
```
**Output yang diharapkan:**
```
[main (root-commit) abc1234] Initial commit - KampusMarket Laravel Project
 XXX files changed, XXXX insertions(+)
 create mode 100644 .env.example
 create mode 100644 .gitignore
 ...
```

---

## 🔗 LANGKAH 3: HUBUNGKAN KE GITHUB REMOTE

### 1️⃣ Tambahkan Remote Repository
**GANTI `<username-anda>` dengan username GitHub Anda!**

```powershell
git remote add origin https://github.com/<username-anda>/kampusmarket.git
```

**Contoh:**
```powershell
git remote add origin https://github.com/johndoe/kampusmarket.git
```

### 2️⃣ Verifikasi Remote (opsional)
```powershell
git remote -v
```
**Output yang diharapkan:**
```
origin  https://github.com/<username-anda>/kampusmarket.git (fetch)
origin  https://github.com/<username-anda>/kampusmarket.git (push)
```

---

## 🚀 LANGKAH 4: PUSH KE GITHUB

### 1️⃣ Push Pertama Kali
```powershell
git push -u origin main
```

**Anda akan diminta login:**
- **Username**: username GitHub Anda
- **Password**: Gunakan **Personal Access Token** (bukan password biasa)

### ⚠️ Cara Membuat Personal Access Token (PAT):

Jika diminta password dan gagal, ikuti langkah ini:

1. Login ke GitHub
2. Klik foto profil → **Settings**
3. Scroll ke bawah → **Developer settings**
4. Pilih **Personal access tokens** → **Tokens (classic)**
5. Klik **Generate new token (classic)**
6. Isi form:
   ```
   Note: KampusMarket Upload
   Expiration: 90 days (atau sesuai kebutuhan)
   
   Centang scope:
   ☑ repo (full control of private repositories)
   ```
7. Klik **Generate token**
8. **SALIN TOKEN** (hanya muncul sekali!)
9. Gunakan token ini sebagai password saat `git push`

**Output sukses:**
```
Enumerating objects: XXX, done.
Counting objects: 100% (XXX/XXX), done.
Delta compression using up to 8 threads
Compressing objects: 100% (XXX/XXX), done.
Writing objects: 100% (XXX/XXX), XXX KiB | XXX MiB/s, done.
Total XXX (delta XX), reused 0 (delta 0)
To https://github.com/<username-anda>/kampusmarket.git
 * [new branch]      main -> main
Branch 'main' set up to track remote branch 'main' from 'origin'.
```

---

## ✅ LANGKAH 5: VERIFIKASI UPLOAD

1. **Buka Repository di GitHub**
   ```
   https://github.com/<username-anda>/kampusmarket
   ```

2. **Periksa File yang Terupload:**
   - ✅ `README.md` tampil di halaman utama
   - ✅ `.env.example` ada di file list
   - ✅ `.gitignore` ada di file list
   - ✅ Folder `app/`, `database/`, `resources/`, dll. terupload
   - ❌ Folder `/vendor` TIDAK terupload (ukuran repo kecil)
   - ❌ Folder `/node_modules` TIDAK terupload
   - ❌ File `.env` TIDAK terupload (aman!)

3. **Periksa Commit History:**
   - Klik tab **"Commits"**
   - Lihat commit pertama: "Initial commit - KampusMarket Laravel Project"

---

## 🔄 LANGKAH 6: UPDATE CODE DI MASA DEPAN

Setelah Anda melakukan perubahan di project lokal:

### 1️⃣ Tambahkan Perubahan
```powershell
git add .
```

### 2️⃣ Commit dengan Pesan Deskriptif
```powershell
git commit -m "Deskripsi perubahan yang dilakukan"
```

**Contoh commit message yang baik:**
```powershell
git commit -m "Add PDF export feature for admin reports"
git commit -m "Fix seller login authentication bug"
git commit -m "Update README installation steps"
```

### 3️⃣ Push ke GitHub
```powershell
git push origin main
```

---

## 🆘 TROUBLESHOOTING

### ❌ Error: "remote origin already exists"
**Solusi:**
```powershell
git remote remove origin
git remote add origin https://github.com/<username-anda>/kampusmarket.git
```

### ❌ Error: "failed to push some refs"
**Artinya:** Remote repository memiliki commit yang belum ada di lokal

**Solusi 1 (Rekomendasi):**
```powershell
git pull origin main --rebase
git push origin main
```

**Solusi 2 (Force Push - HATI-HATI!):**
```powershell
git push -f origin main
```
⚠️ **Hanya gunakan jika Anda yakin tidak ada perubahan penting di remote!**

### ❌ Error: "Authentication failed"
**Solusi:**
- Pastikan menggunakan **Personal Access Token** bukan password
- Generate token baru jika sudah expired

### ❌ Salah Commit (belum push)
**Undo commit terakhir (file tetap ada):**
```powershell
git reset --soft HEAD~1
```

**Undo commit dan hapus perubahan:**
```powershell
git reset --hard HEAD~1
```

### ❌ File Sensitif Tercommit (sudah push)
**Hapus file dari history (berbahaya!):**
```powershell
git filter-branch --force --index-filter "git rm --cached --ignore-unmatch .env" --prune-empty --tag-name-filter cat -- --all
git push origin --force --all
```

---

## 📝 TIPS BEST PRACTICES

### ✅ DO (Lakukan):
1. **Selalu cek `.gitignore` sebelum commit pertama**
2. **Buat commit dengan pesan yang jelas dan deskriptif**
3. **Gunakan `.env.example` untuk dokumentasi environment variables**
4. **Push secara berkala (jangan tunggu sampai project selesai)**
5. **Buat branch untuk fitur baru:**
   ```powershell
   git checkout -b feature/pdf-export
   git push origin feature/pdf-export
   ```

### ❌ DON'T (Jangan):
1. **Jangan commit file `.env` (berisi credential sensitif)**
2. **Jangan commit folder `/vendor` dan `/node_modules` (terlalu besar)**
3. **Jangan force push jika bekerja dalam tim**
4. **Jangan commit dengan pesan "fix", "update", "changes" tanpa konteks**
5. **Jangan commit file IDE (`.vscode`, `.idea`) jika tidak diperlukan**

---

## 📋 CHECKLIST AKHIR

Sebelum submit tugas atau share repository:

- [ ] README.md sudah lengkap dengan instruksi instalasi
- [ ] .env.example tersedia (tanpa APP_KEY dan credential asli)
- [ ] Semua commit message jelas dan profesional
- [ ] Tidak ada file sensitif (.env, credential, API keys)
- [ ] Screenshot aplikasi ditambahkan di README (opsional)
- [ ] Dokumentasi API jika ada (opsional)
- [ ] License file jika diperlukan

---

## 🎓 UNTUK DOSEN/PENGUJI

Instruksi untuk clone dan menjalankan project ini:

```bash
# Clone repository
git clone https://github.com/<username-mahasiswa>/kampusmarket.git
cd kampusmarket

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database (sesuaikan .env)
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Jalankan server
php artisan serve

# Akses:
# - Frontend: http://localhost:8000
# - Admin: http://localhost:8000/admin (admin@kampusmarket.com / password)
# - Seller: http://localhost:8000/seller (budi@elektronik.com / password)
```

---

## 📞 BANTUAN

Jika mengalami masalah:

1. **Baca error message dengan teliti**
2. **Cek dokumentasi Git:** https://git-scm.com/doc
3. **Cek dokumentasi GitHub:** https://docs.github.com
4. **Stack Overflow:** https://stackoverflow.com/questions/tagged/git

---

**🎉 Selamat! Project KampusMarket Anda sekarang sudah online di GitHub!**

Bagikan link repository Anda:
```
https://github.com/<username-anda>/kampusmarket
```
