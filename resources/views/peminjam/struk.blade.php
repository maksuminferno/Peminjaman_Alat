<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pengembalian - PJN{{ sprintf('%03d', $peminjaman->id_peminjaman) }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        table th {
            background-color: #f0f0f0;
        }
        .total-section {
            border-top: 2px dashed #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed #333;
            font-size: 11px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-dikembalikan {
            background-color: #d4edda;
            color: #155724;
        }
        .status-dipinjam {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-belum_dikembalikan {
            background-color: #f8d7da;
            color: #721c24;
        }
        .btn-print {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
        @media print {
            .btn-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div style="text-align: right; margin-bottom: 10px;">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Struk
        </button>
    </div>

    <div class="header">
        <h2>STRUK PENGEMBALIAN ALAT</h2>
        <p>Sistem Peminjaman Alat</p>
        <p>Jl. Contoh No. 123, Kota Contoh</p>
        <p>Telp: (021) 1234-5678</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">No. Struk</span>
            <span>: PJN{{ sprintf('%03d', $peminjaman->id_peminjaman) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nama Peminjam</span>
            <span>: {{ $peminjaman->user->nama ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Pinjam</span>
            <span>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Kembali Rencana</span>
            <span>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Kembali Real</span>
            <span>: {{ $peminjaman->pengembalian ? \Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_kembali)->format('d M Y') : '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span>: 
                <span class="status-badge status-{{ $peminjaman->status }}">
                    {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                </span>
            </span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Alat</th>
                <th>Jumlah</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman->detailPeminjaman as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->alat->nama_alat }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>
                    @if($peminjaman->pengembalian)
                        {{ ucfirst($peminjaman->pengembalian->kondisi_alat ?? 'baik') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($peminjaman->pengembalian && $peminjaman->pengembalian->denda > 0)
    <div class="total-section">
        <div class="total-row">
            <span>Denda Keterlambatan:</span>
            <span>Rp {{ number_format($peminjaman->pengembalian->denda_keterlambatan ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>Denda Kerusakan:</span>
            <span>Rp {{ number_format($peminjaman->pengembalian->denda_kerusakan ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="total-row" style="font-size: 16px; margin-top: 10px; font-weight: bold;">
            <span>TOTAL DENDA:</span>
            <span>Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</span>
        </div>
        
        @if($peminjaman->pengembalian->denda_keterlambatan > 0)
        <div style="margin-top: 15px; padding-top: 10px; border-top: 1px dashed #ccc;">
            <p style="font-size: 12px; margin: 0; color: #666;">
                <strong>Rincian Keterlambatan:</strong><br>
                @php
                    $hariTerlambat = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->diffInDays(\Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_kembali));
                @endphp
                Terlambat {{ $hariTerlambat }} hari x Rp {{ number_format(config('denda.tarif_keterlambatan_per_hari'), 0, ',', '.') }} = Rp {{ number_format($peminjaman->pengembalian->denda_keterlambatan, 0, ',', '.') }}
            </p>
        </div>
        @endif
        
        @if($peminjaman->pengembalian->denda_kerusakan > 0)
        <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ccc;">
            <p style="font-size: 12px; margin: 0; color: #666;">
                <strong>Rincian Kerusakan:</strong><br>
                Kondisi alat: {{ ucfirst($peminjaman->pengembalian->kondisi_alat) }}<br>
                @if($peminjaman->pengembalian->deskripsi_kerusakan)
                Deskripsi: {{ $peminjaman->pengembalian->deskripsi_kerusakan }}<br>
                @endif
            </p>
        </div>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Struk ini dibuat secara otomatis oleh sistem.</p>
        <p>Terima kasih telah menggunakan layanan kami.</p>
        <p>{{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    <script>
        // Auto print on load (optional, can be removed if not needed)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
