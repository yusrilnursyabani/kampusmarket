<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Toko per Provinsi</title>
    <style>
        @page { margin: 30px; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #1f2933; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-top: 24px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background: #f3f4f6; font-weight: 600; }
        .meta { font-size: 11px; color: #6b7280; }
        .badge { padding: 3px 7px; border-radius: 999px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>
    <header>
        <h1>KampusMarket &mdash; Laporan Persebaran Toko</h1>
        <p class="meta">Dibuat pada {{ $generatedAt->format('d F Y H:i') }}</p>
    </header>

    <h2>Ringkasan per Provinsi</h2>
    <table>
        <thead>
            <tr>
                <th>Provinsi</th>
                <th>Total Toko</th>
                <th>Toko Aktif</th>
                <th>Toko Tidak Aktif</th>
            </tr>
        </thead>
        <tbody>
            @foreach($provinceSummaries as $summary)
                <tr>
                    <td>{{ $summary->provinsi ?? '-' }}</td>
                    <td>{{ $summary->total_toko }}</td>
                    <td>{{ $summary->toko_aktif }}</td>
                    <td>{{ $summary->toko_tidak_aktif }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Detail Toko</h2>
    @foreach($groupedSellers as $province => $sellers)
        <table style="margin-top: 16px;">
            <thead>
                <tr>
                    <th colspan="4">Provinsi: {{ $province ?: '-' }} ({{ $sellers->count() }} toko)</th>
                </tr>
                <tr>
                    <th>Nama Toko</th>
                    <th>Status</th>
                    <th>Lokasi</th>
                    <th>PIC</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sellers as $seller)
                    <tr>
                        <td>{{ $seller->nama_toko }}</td>
                        <td>
                            <span class="badge {{ $seller->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $seller->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>{{ $seller->kota_kabupaten }}</td>
                        <td>{{ $seller->nama_pic }} &bull; {{ $seller->no_hp_pic }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
