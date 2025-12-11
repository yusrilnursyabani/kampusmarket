<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>(SRS-MartPlace-09) Laporan Daftar Akun Penjual Berdasarkan Status</title>
    <style>
        @page { margin: 25px; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #000; }
        h1 { font-size: 16px; margin-bottom: 2px; text-align: center; }
        h2 { font-size: 12px; margin: 0 0 15px 0; color: #666; text-align: center; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; font-weight: 600; text-align: center; }
        td { vertical-align: top; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <header>
        <h1>(SRS-MartPlace-09)</h1>
        <h1>Laporan Daftar Akun Penjual Berdasarkan Status</h1>
        <h2>Tanggal dibuat: {{ $generatedAt->format('d-m-Y') }} oleh {{ $generatedBy }}</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama User</th>
                <th width="25%">Nama PIC</th>
                <th width="30%">Nama Toko</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sellers as $seller)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ optional($seller->user)->name ?? $seller->email_pic }}</td>
                    <td>{{ $seller->nama_pic }}</td>
                    <td>{{ $seller->nama_toko }}</td>
                    <td class="center">{{ $seller->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 15px; font-size: 10px; color: #666;">
        ***) urutkan berdasarkan status (aktif dulu baru tidak aktif)
    </p>
</body>
</html>
