<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nilai Kelas {{ $kelas->nama_kelas }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }
        h3, h4, h5 {
            text-align: center;
            margin-bottom: 5px;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
        }
        th {
            background-color: #f2f2f2;
        }
        .info-box {
            margin-bottom: 20px;
        }
        .student-name {
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 5px;
        }
        .signature-box {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>DAFTAR NILAI SISWA</h3>
        <h4>KELAS {{ strtoupper($kelas->nama_kelas) }}</h4>
        <h5>{{ strtoupper($sekolah->nama ?? 'SEKOLAH') }}</h5>
        <h5>TAHUN PELAJARAN {{ $tahunAkademik ? $tahunAkademik->tahun_akademik : date('Y').'/'.((int)date('Y')+1) }} - SEMESTER {{ $tahunAkademik ? $tahunAkademik->semester : '' }}</h5>
    </div>

    @foreach($siswa as $s)
        <div class="info-box">
            <div class="student-name">{{ $s->nama }}</div>
            <table class="table-sm">
                <tr>
                    <td width="150">NISN</td>
                    <td width="10">:</td>
                    <td>{{ $s->nisn }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $s->jns_kelamin }}</td>
                </tr>
            </table>
        </div>

        <table class="table-sm text-center">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Mata Pelajaran</th>
                    <th width="15%">UTS</th>
                    <th width="15%">UAS</th>
                    <th width="25%">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $nilai = $s->mapel()->wherePivot('thnakademik_id', $tahunAkademik->id)->get();
                    $total_uts = 0;
                    $total_uas = 0;
                    $count = count($nilai);
                @endphp

                @forelse($nilai as $index => $n)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left">{{ $n->nama_mapel }}</td>
                        <td>{{ $n->pivot->uts }}</td>
                        <td>{{ $n->pivot->uas }}</td>
                        <td>{{ $n->pivot->status }}</td>
                    </tr>
                    @php
                        $total_uts += $n->pivot->uts;
                        $total_uas += $n->pivot->uas;
                    @endphp
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada nilai</td>
                    </tr>
                @endforelse

                @if($count > 0)
                    <tr>
                        <th colspan="2" class="text-right">Rata-rata</th>
                        <th>{{ number_format($total_uts / $count, 2) }}</th>
                        <th>{{ number_format($total_uas / $count, 2) }}</th>
                        <th></th>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="signature-box">
            <div>{{ $sekolah->alamat ? trim(explode(',', $sekolah->alamat)[0]) : 'Limbangan' }}, {{ date('d F Y') }}</div>
            <div>Wali Kelas</div>
            <div style="height: 60px;"></div>
            <div>{{ $kelas->guru ? $kelas->guru->nama : '___________________________' }}</div>
            <div>NIP. {{ $kelas->guru && $kelas->guru->nip ? $kelas->guru->nip : '' }}</div>
        </div>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html> 