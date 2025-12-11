<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>(SRS-MartPlace-13) Laporan Daftar Produk Berdasarkan Rating</title>
    <style>
        @page { margin: 20px; size: A4 landscape; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #000; }
        h1 { font-size: 16px; margin-bottom: 2px; }
        h2 { font-size: 12px; margin: 0 0 15px 0; color: #666; font-weight: normal; font-style: italic; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px 8px; text-align: left; }
        th { background: #f0f0f0; font-weight: 600; text-align: center; }
        td { vertical-align: top; }
        .center { text-align: center; }
        .right { text-align: right; }
        .footer-note { margin-top: 10px; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <h1>(SRS-MartPlace-13)</h1>
    <h1>Laporan Daftar Produk Berdasarkan Rating</h1>
    <h2>Tanggal dibuat: {{ $generatedAt->format('d-m-Y') }} oleh {{ $seller->nama_pic }}</h2>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Produk</th>
                <th width="20%">Kategori</th>
                <th width="15%">Harga</th>
                <th width="15%">Stock</th>
                <th width="15%">Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $product['nama_produk'] }}</td>
                    <td>{{ $product['kategori'] }}</td>
                    <td class="right">{{ number_format($product['harga'], 0, ',', '.') }}</td>
                    <td class="center">{{ $product['stok'] }}</td>
                    <td class="center">{{ $product['rating_display'] ?? $product['rating'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer-note">***) urutkan berdasarkan rating</p>
</body>
</html>
