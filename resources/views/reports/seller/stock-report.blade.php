<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 20px; size: A4 landscape; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #000; }
        h1 { font-size: 16px; margin-bottom: 2px; text-align: center; }
        h2 { font-size: 12px; margin: 0 0 5px 0; color: #666; text-align: center; font-weight: normal; }
        .seller-info { font-size: 11px; margin-bottom: 15px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px 6px; text-align: left; }
        th { background: #f0f0f0; font-weight: 600; text-align: center; }
        td { vertical-align: top; }
        .center { text-align: center; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <header>
        <h1>{{ $title }}</h1>
        <h2>Tanggal dibuat: {{ $generatedAt->format('d-m-Y') }}</h2>
        <div class="seller-info">
            <strong>Toko:</strong> {{ $seller->nama_toko }} | 
            <strong>PIC:</strong> {{ $seller->nama_pic }}
        </div>
    </header>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Produk</th>
                <th width="20%">Kategori</th>
                <th width="15%">Harga</th>
                <th width="10%">Stock</th>
                <th width="15%">Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $product['nama_produk'] }}</td>
                    <td>{{ $product['kategori'] }}</td>
                    <td class="right">Rp {{ number_format($product['harga'], 0, ',', '.') }}</td>
                    <td class="center">{{ $product['stok'] }}</td>
                    <td class="center">{{ $product['rating'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
