<!DOCTYPE html>
<html>
<head>
    <title>Detail Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .status {
            padding: 5px 10px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
        }
        .status-lunas {
            background-color: #28a745;
        }
        .status-belum {
            background-color: #dc3545;
        }
        .bukti-pembayaran {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('foto/tutwuri.png') }}" class="logo" alt="Logo">
        <h2>Detail Pembayaran</h2>
        <p>{{ $sekolah->nama }}</p>
    </div>

    <table class="table">
        <tr>
            <th>NISN</th>
            <td>{{ $item->nisn }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td>{{ $item->nama }}</td>
        </tr>
        <tr>
            <th>Kelas</th>
            <td>{{ $item->kelas }}</td>
        </tr>
        <tr>
            <th>Jenis Pembayaran</th>
            <td>
                @if ($item->jenispem == null)
                    Jenis Pembayaran Terhapus
                @else
                    {{ $item->jenispem->jenis }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Jumlah Pembayaran</th>
            <td>Rp. {{ number_format($item->jum_pemb) }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ $item->tanggal }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <span class="status status-{{ $item->status == 'lunas' ? 'lunas' : 'belum' }}">
                    {{ $item->status == 'lunas' ? 'Sudah Lunas' : 'Belum Lunas' }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Keterangan</th>
            <td>{{ $item->keterangan }}</td>
        </tr>
    </table>

    @if($item->bukti_pembayaran)
        <div>
            <h3>Bukti Pembayaran</h3>
            <img src="{{ storage_path('app/public/bukti_pembayaran/' . $item->bukti_pembayaran) }}" class="bukti-pembayaran" alt="Bukti Pembayaran">
        </div>
    @endif

    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 