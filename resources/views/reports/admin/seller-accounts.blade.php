<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Akun Penjual</title>
    <style>
        @page { margin: 32px; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #1f2933; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin: 0; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background: #f3f4f6; font-weight: 600; }
        .badge { padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .meta { font-size: 11px; color: #6b7280; margin-top: 2px; }
    </style>
</head>
<body>
    <header>
        <h1>KampusMarket &mdash; Laporan Akun Penjual</h1>
        <h2>Dibuat pada {{ $generatedAt->format('d F Y H:i') }}</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Toko</th>
                <th>PIC / Kontak</th>
                <th>Lokasi</th>
                <th>Status Verifikasi</th>
                <th>Keaktifan</th>
                <th>Bergabung</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sellers as $seller)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $seller->nama_toko }}</strong>
                        <div class="meta">{{ $seller->email_pic }}</div>
                    </td>
                    <td>
                        {{ $seller->nama_pic }}<br>
                        <span class="meta">{{ $seller->no_hp_pic }}</span>
                    </td>
                    <td>{{ $seller->kota_kabupaten }}, {{ $seller->provinsi }}</td>
                    <td>
                        @php
                            $verificationClass = match ($seller->status_verifikasi) {
                                'diterima' => 'badge-success',
                                'ditolak' => 'badge-danger',
                                default => 'badge-warning',
                            };
                        @endphp
                        <span class="badge {{ $verificationClass }}">{{ ucfirst($seller->status_verifikasi) }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $seller->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $seller->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td>{{ $seller->created_at?->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
