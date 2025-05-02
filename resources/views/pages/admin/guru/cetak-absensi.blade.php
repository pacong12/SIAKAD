<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 70px;
            height: auto;
            display: block;
            margin: 0 auto 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }
        .subtitle {
            font-size: 14px;
            margin: 5px 0;
        }
        .address {
            font-size: 12px;
            margin: 5px 0 15px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        td {
            padding: 6px 8px;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            width: 100%;
            margin-top: 20px;
        }
        .summary-item {
            margin-bottom: 5px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
            padding-right: 50px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            display: inline-block;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
            $sekolah = \App\Sekolah::first();
        @endphp
        
        @if($sekolah && $sekolah->logo)
            <img src="{{ public_path('storage/'.$sekolah->logo) }}" class="logo">
        @endif
        
        <div class="title">{{ $sekolah ? $sekolah->nama_sekolah : 'SEKOLAH' }}</div>
        <div class="subtitle">{{ $sekolah ? $sekolah->tingkat : 'TINGKAT PENDIDIKAN' }}</div>
        <div class="address">{{ $sekolah ? $sekolah->alamat.', Telp: '.$sekolah->telepon : 'Alamat Sekolah' }}</div>
        <hr style="border: 1px solid #000;">
    </div>
    
    <div class="report-title">
        {{ $judul ?? 'LAPORAN ABSENSI SISWA' }}
    </div>
    
    <div class="summary">
        <div style="float: left; width: 70%;">
            <table border="0" style="border: none;">
                <tr style="border: none;">
                    <td style="border: none; width: 150px;"><strong>Total Data</strong></td>
                    <td style="border: none; width: 10px;">:</td>
                    <td style="border: none;">{{ count($absensi) }} data</td>
                </tr>
                @if(isset($rangkuman))
                <tr style="border: none;">
                    <td style="border: none;"><strong>Status Hadir</strong></td>
                    <td style="border: none;">:</td>
                    <td style="border: none;">{{ $rangkuman['hadir'] ?? 0 }} siswa</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Status Sakit</strong></td>
                    <td style="border: none;">:</td>
                    <td style="border: none;">{{ $rangkuman['sakit'] ?? 0 }} siswa</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Status Izin</strong></td>
                    <td style="border: none;">:</td>
                    <td style="border: none;">{{ $rangkuman['izin'] ?? 0 }} siswa</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Status Alpa</strong></td>
                    <td style="border: none;">:</td>
                    <td style="border: none;">{{ $rangkuman['alpa'] ?? 0 }} siswa</td>
                </tr>
                @endif
            </table>
        </div>
        <div style="clear: both;"></div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="15%">NISN</th>
                <th width="25%">Nama Siswa</th>
                <th width="15%">Kelas</th>
                <th width="10%">Status</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensi as $index => $a)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $a->siswa->nisn ?? '-' }}</td>
                    <td>{{ $a->siswa->nama ?? '-' }}</td>
                    <td>{{ $a->kelas->nama_kelas ?? '-' }}</td>
                    <td class="text-center">
                        @if($a->status == 'hadir')
                            <span class="badge badge-success">Hadir</span>
                        @elseif($a->status == 'sakit')
                            <span class="badge badge-warning">Sakit</span>
                        @elseif($a->status == 'izin')
                            <span class="badge badge-info">Izin</span>
                        @elseif($a->status == 'alpa')
                            <span class="badge badge-danger">Alpa</span>
                        @endif
                    </td>
                    <td>{{ $a->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data absensi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="signature">
        <p>{{ $sekolah ? $sekolah->kota : 'Kota' }}, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
        <p>Guru</p>
        <br><br><br>
        <p><strong>{{ auth()->user()->name }}</strong></p>
    </div>
</body>
</html> 