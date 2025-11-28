<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Produk & Rating</title>
    <style>
        @page { margin: 30px; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #1f2933; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        p.meta { font-size: 11px; color: #6b7280; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background: #f3f4f6; font-weight: 600; }
    </style>
</head>
<body>
    <header>
        <h1>KampusMarket &mdash; Laporan Produk & Rating</h1>
        <p class="meta">Dibuat pada {{ $generatedAt->format('d F Y H:i') }}</p>
    </header>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Toko</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Rata-rating</th>
                <th>Total Review</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product['nama_produk'] }}</td>
                    <td>{{ $product['nama_toko'] }}</td>
                    <td>{{ $product['kategori'] }}</td>
                    <td>{{ $product['stok'] }}</td>
                    <td>{{ number_format($product['rata_rating'], 2) }}</td>
                    <td>{{ $product['total_review'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
