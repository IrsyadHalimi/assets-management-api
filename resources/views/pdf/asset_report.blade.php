<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aset</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            margin-bottom: 5px;
            color: #007bff;
        }
        .header p {
            font-size: 11px;
            color: #777;
        }
        .report-info {
            margin-bottom: 15px;
        }
        .report-info table {
            width: 100%;
            font-size: 11px;
        }
        .report-info td {
            padding: 4px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .table th, .table td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .summary {
            margin-top: 20px;
            font-size: 12px;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 10px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN ASET</h2>
        <p>Perusahaan XYZ · {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
    </div>

    <div class="report-info">
        <table>
            <tr>
                <td><strong>Disiapkan Oleh:</strong> Admin Sistem</td>
                <td><strong>Tanggal Laporan:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Departemen:</strong> Inventory & Asset</td>
                <td><strong>Jumlah Aset:</strong> {{ count($assets) }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Aset</th>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $index => $asset)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->asset_code }}</td>
                    <td>{{ $categories->firstWhere('id', $asset->category_id)?->name ?? 'N/A' }}</td>
                    <td>{{ $asset->location }}</td>
                    <td>{{ $asset->quantity }}</td>
                    <td>Rp {{ number_format($asset->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($asset->amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Total Nilai Aset:</strong>
            Rp {{ number_format($assets->sum('amount'), 0, ',', '.') }}
        </p>
    </div>

    <div class="footer">
        © {{ date('Y') }} Perusahaan XYZ. Laporan dibuat otomatis oleh sistem.
    </div>

</body>
</html>
