<!DOCTYPE html>
<html>
<head>
    <title>Nilai Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .school-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .student-info {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="school-info">
        <h2>{{ $sekolah->nama_sekolah }}</h2>
        <p>{{ $sekolah->alamat }}</p>
        <p>Telp: {{ $sekolah->telepon }}</p>
    </div>

    <div class="header">
        <h2>LAPORAN NILAI SISWA</h2>
        <h3>Tahun Akademik: {{ $thnakademik->tahun_akademik }} - Semester {{ $thnakademik->semester }}</h3>
    </div>

    <div class="student-info">
        <p><strong>Nama Siswa:</strong> {{ $siswa->nama }}</p>
        <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
        <p><strong>Kelas:</strong> {{ $siswa->kelasAktif->first()->nama_kelas ?? '-' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Mata Pelajaran</th>
                <th>Guru Mapel</th>
                <th class="text-center">Nilai UTS</th>
                <th class="text-center">Nilai UAS</th>
                <th class="text-center">Rata-rata</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswa->mapel as $index => $mapel)
            @php
                $rataRataMapel = round(($mapel->pivot->uts + $mapel->pivot->uas) / 2, 2);
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $mapel->nama_mapel }}</td>
                <td>{{ $guruMapel[$mapel->id] ?? '-' }}</td>
                <td class="text-center">{{ $mapel->pivot->uts }}</td>
                <td class="text-center">{{ $mapel->pivot->uas }}</td>
                <td class="text-center">{{ $rataRataMapel }}</td>
                <td class="text-center">{{ $mapel->pivot->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Rata-rata Keseluruhan:</strong> {{ $rataRata }}</p>
    </div>

    <div class="footer">
        <p>{{ $sekolah->kota }}, {{ date('d F Y') }}</p>
        <br><br><br>
        <p>Wali Kelas</p>
        <br><br><br>
        <p>{{ $waliKelas ? $waliKelas->nama : '-' }}</p>
    </div>
</body>
</html> 