<x-mail::message>
# Pemberitahuan Pengajuan Toko

Halo {{ $seller->nama_pic }},

Terima kasih telah mendaftar sebagai penjual di **{{ config('app.name') }}**.

Setelah meninjau pengajuan Anda untuk **{{ $seller->nama_toko }}**, dengan berat hati kami harus menginformasikan bahwa pengajuan Anda **belum dapat kami setujui** saat ini.

## Alasan Penolakan:

{{ $seller->alasan_penolakan ?? 'Tidak ada alasan yang diberikan.' }}

---

Kami mengundang Anda untuk memperbaiki informasi yang diperlukan dan mendaftar kembali di kemudian hari.

Jika Anda memiliki pertanyaan atau membutuhkan klarifikasi lebih lanjut, silakan hubungi tim support kami.

Terima kasih atas pengertian Anda.

Salam,<br>
Tim {{ config('app.name') }}
</x-mail::message>
