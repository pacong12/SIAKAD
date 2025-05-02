<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Laporan Data Absen Siswa</title>
    <style>
      table {
        border-collapse: collapse;
        width: 100%;
      }
      table, th, td {
        border: 1px solid black;
      }
      th, td {
        padding: 5px;
        text-align: center;
      }
      th {
        background-color: #f2f2f2;
      }
      .header {
        text-align: center;
        margin-bottom: 20px;
      }
      .logo {
        text-align: center;
        margin-bottom: 10px;
      }
    </style>
  </head>
  <body>
    <div class="header">
      <div class="logo">
        <!-- Logo sekolah jika ada -->
      </div>
      <h3>Laporan Data Absen Siswa</h3>
      <p>Tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <table class="table table-striped table-bordered text-center table-sm">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absen as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $a->siswa->nama ?? 'Data tidak tersedia' }}</td>
                    <td>{{ $a->kelas->nama_kelas ?? 'Data tidak tersedia' }}</td>
                    <td>{{ date('d/m/Y', strtotime($a->tanggal)) }}</td>
                    <td>
                      @if($a->status == 'hadir')
                        Hadir
                      @elseif($a->status == 'sakit')
                        Sakit
                      @elseif($a->status == 'izin')
                        Izin
                      @elseif($a->status == 'alpa')
                        Alpa
                      @else
                        {{ $a->status }}
                      @endif
                    </td>
                    <td>{{ $a->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Data Kosong
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
      <p>................., {{ date('d F Y') }}</p>
      <br><br><br>
      <p>______________________</p>
      <p>Kepala Sekolah</p>
    </div>
  </body>
</html>