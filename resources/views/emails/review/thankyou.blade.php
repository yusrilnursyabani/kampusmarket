<x-mail::message>
# ✨ Terima Kasih, {{ $review->nama_pengunjung }}!

Kami sangat menghargai waktu Anda untuk memberikan review pada produk **{{ $product->nama_produk }}**.

## Review Anda:

**Rating:** {{ str_repeat('⭐', $review->rating) }} ({{ $review->rating }}/5)

**Komentar:**
> {{ $review->isi_komentar }}

---

Review Anda sangat membantu calon pembeli lain dalam membuat keputusan yang tepat dan membantu penjual untuk terus meningkatkan kualitas produk mereka.

<x-mail::button :url="route('products.show', $product->slug)">
Lihat Produk & Review Anda
</x-mail::button>

### Jelajahi Produk Lainnya

Kami memiliki ribuan produk menarik dari berbagai seller kampus. Jangan lupa untuk terus menjelajahi katalog kami!

<x-mail::button :url="route('products.index')" color="success">
Lihat Katalog Produk
</x-mail::button>

Terima kasih telah menjadi bagian dari komunitas KampusMarket! 💚

Salam hangat,<br>
Tim {{ config('app.name') }}
</x-mail::message>
