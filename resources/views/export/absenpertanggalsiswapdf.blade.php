<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Laporan Data Absen Siswa Per Tanggal</title>
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
      <p>Periode: {{ isset($tglawal) ? date('d/m/Y', strtotime($tglawal)) : '' }} 
         s/d {{ isset($tglakhir) ? date('d/m/Y', strtotime($tglakhir)) : '' }}</p>
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
            @forelse ($absenPertanggal as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->siswa->nama ?? 'Data tidak tersedia' }}</td>
                    <td>{{ $p->kelas->nama_kelas ?? 'Data tidak tersedia' }}</td>
                    <td>{{ date('d/m/Y', strtotime($p->tanggal)) }}</td>
                    <td>
                      @if($p->status == 'hadir')
                        Hadir
                      @elseif($p->status == 'sakit')
                        Sakit
                      @elseif($p->status == 'izin')
                        Izin
                      @elseif($p->status == 'alpa')
                        Alpa
                      @else
                        {{ $p->status }}
                      @endif
                    </td>
                    <td>{{ $p->keterangan ?? '-' }}</td>
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
    
    <div class="row mt-4">
      <div class="col-12">
        <table class="table table-bordered" style="width: 50%; margin-left: auto;">
          <tr>
            <th>Status</th>
            <th>Jumlah</th>
          </tr>
          <tr>
            <td>Hadir</td>
            <td>{{ $absenPertanggal->where('status', 'hadir')->count() }}</td>
          </tr>
          <tr>
            <td>Sakit</td>
            <td>{{ $absenPertanggal->where('status', 'sakit')->count() }}</td>
          </tr>
          <tr>
            <td>Izin</td>
            <td>{{ $absenPertanggal->where('status', 'izin')->count() }}</td>
          </tr>
          <tr>
            <td>Alpa</td>
            <td>{{ $absenPertanggal->where('status', 'alpa')->count() }}</td>
          </tr>
        </table>
      </div>
    </div>

    <div style="margin-top: 30px; text-align: right;">
      <p>................., {{ date('d F Y') }}</p>
      <br><br><br>
      <p>______________________</p>
      <p>Kepala Sekolah</p>
    </div>
  </body>
</html>