<x-mail::message>
# 🎉 Selamat, {{ $seller->nama_toko }}!

Kami dengan senang hati menginformasikan bahwa **toko Anda telah disetujui** untuk bergabung di platform KampusMarket.

## Informasi Akun Anda

- **Nama Toko:** {{ $seller->nama_toko }}
- **Email Login:** {{ $seller->email_pic }}
- **Status:** Aktif

Anda sekarang dapat login ke panel seller dan mulai mengelola produk Anda.

<x-mail::button :url="$loginUrl">
Login ke Panel Seller
</x-mail::button>

## Langkah Selanjutnya:

1. Login menggunakan email dan password yang telah Anda daftarkan
2. Lengkapi profil toko Anda
3. Tambahkan produk pertama Anda
4. Mulai berjualan di KampusMarket!

Jika Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi tim support kami.

Selamat berjualan! 🚀

Salam hangat,<br>
Tim {{ config('app.name') }}
</x-mail::message>
