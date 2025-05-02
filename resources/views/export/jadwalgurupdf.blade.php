<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Mengajar Guru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2, .header h3 {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .guru-info {
            margin-bottom: 15px;
            font-weight: bold;
        }
        .signature {
            float: right;
            width: 30%;
            text-align: center;
            margin-top: 30px;
        }
        .signature p {
            margin: 4px 0;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>JADWAL MENGAJAR GURU</h2>
            <h3>{{ strtoupper($sekolah ? $sekolah->nama : 'SEKOLAH') }}</h3>
            <h3>TAHUN PELAJARAN {{ $tahunAkademik ? $tahunAkademik->tahun_akademik : date('Y').'/'.((int)date('Y')+1) }}</h3>
        </div>
        
        @if ($guru)
        <div class="guru-info">
            Nama Guru: {{ $guru->nama }}
        </div>
        @endif
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->mapel ? $item->mapel->nama_mapel : 'Mapel Terhapus' }}</td>
                    <td>{{ $item->kelas ? $item->kelas->nama_kelas : 'Kelas Terhapus' }}</td>
                    <td>{{ $item->hari }}</td>
                    <td>{{ substr($item->jam_mulai, 0, 5) }}</td>
                    <td>{{ substr($item->jam_selesai, 0, 5) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">Tidak ada jadwal</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="signature">
            <p>{{ $sekolah && $sekolah->alamat ? trim(explode(',', $sekolah->alamat)[0]) : 'Limbangan' }}, {{ date('d F Y') }}</p>
            <p>Mengetahui,</p>
            <p>Kepala Sekolah</p>
            <br><br><br><br>
            <p>{{ $sekolah ? $sekolah->kepala_sklh : '___________________' }}</p>
            <p>NIP: </p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>
