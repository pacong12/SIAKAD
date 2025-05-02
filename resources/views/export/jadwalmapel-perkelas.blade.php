<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Force landscape orientation - multiple approaches for better browser support -->
    <style type="text/css" media="print">
      @page {
        size: landscape;
        margin: 1cm;
      }
    </style>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Jadwal Pelajaran {{ $kelas->nama_kelas }}</title>
    <style>
      @page {
        margin: 0.5cm;
      }
      body {
        font-family: Arial, sans-serif;
        font-size: 8pt;
        margin: 0;
        padding: 0;
      }
      h3, h4, h5 {
        font-weight: bold;
        text-align: center;
        margin-bottom: 0;
        margin-top: 5px;
      }
      h3 { font-size: 12pt; }
      h4 { font-size: 11pt; }
      h5 { font-size: 10pt; }
      .header {
        margin-bottom: 5px;
        border-bottom: 2px solid #000;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
      }
      table, th, td {
        border: 1px solid black;
      }
      th, td {
        padding: 2px;
        text-align: center;
        font-size: 8pt;
        overflow: hidden;
      }
      th {
        background-color: #f0f0f0;
      }
      .kelas-col {
        width: 5%;
      }
      .jam-col {
        width: 5%;
      }
      .waktu-col {
        width: 8%;
      }
      .hari-col {
        width: 13.6%;
      }
      .guru {
        font-size: 7pt;
      }
      .bg-istirahat {
        background-color: #dddddd;
      }
      .signature {
        margin-top: 10px;
        margin-bottom: 10px;
        text-align: right;
      }
      .signature-space {
        height: 40px;
      }
      html, body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .landscape-container {
        width: 29.7cm;
        min-height: 21cm;
        padding: 1cm;
        margin: 0 auto;
      }
      @media print {
        body {
          width: 29.7cm;
          height: 21cm;
        }
        .landscape-container {
          width: 100%;
          height: 100%;
          overflow: visible;
        }
        /* Add these properties for better print handling */
        html {
          overflow: hidden;
        }
        body {
          overflow: visible !important;
        }
        /* Ensure backgrounds print properly in all browsers */
        * {
          -webkit-print-color-adjust: exact !important;
          print-color-adjust: exact !important;
          color-adjust: exact !important;
        }
      }
    </style>
  </head>
  <body>
    <div class="header">
      <h3>JADWAL PELAJARAN</h3>
      <h4>{{ strtoupper($sekolah->nama ?? 'SEKOLAH') }}</h4>
      <h5>TAHUN PELAJARAN {{ $tahunAkademik ? $tahunAkademik->tahun_akademik : date('Y').'/'.((int)date('Y')+1) }}</h5>
    </div>

    <!-- Mengumpulkan jadwal per hari dan jam -->
    @php
      $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      $jamMulai = [];
      
      // Mengumpulkan semua jam mulai yang unik
      foreach ($jadwal as $j) {
        if (!in_array($j->jam_mulai, $jamMulai)) {
          $jamMulai[] = $j->jam_mulai;
        }
      }
      
      // Mengurutkan jam
      sort($jamMulai);
      
      // Tambahkan jam istirahat
      $jamIstirahat = ['09:20:00', '11:20:00'];
      foreach ($jamIstirahat as $jam) {
        if (!in_array($jam, $jamMulai)) {
          $jamMulai[] = $jam;
        }
      }
      
      // Urutkan kembali jam
      sort($jamMulai);
      
      // Mengelompokkan jadwal berdasarkan hari dan jam
      $jadwalArr = [];
      foreach ($hari as $h) {
        foreach ($jamMulai as $jam) {
          $jadwalArr[$jam][$h] = null;
        }
      }
      
      // Mengisi array jadwal
      foreach ($jadwal as $j) {
        $jadwalArr[$j->jam_mulai][$j->hari] = $j;
      }
    @endphp

    <table>
      <thead>
        <tr>
          <th class="kelas-col" rowspan="2">Kls</th>
          <th class="jam-col" rowspan="2">Jam</th>
          <th class="waktu-col" rowspan="2">WAKTU</th>
          <th colspan="6">HARI</th>
        </tr>
        <tr>
          @foreach ($hari as $h)
            <th class="hari-col">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach ($jamMulai as $index => $jam)
          <tr>
            @if ($index === 0)
              <td rowspan="{{ count($jamMulai) }}">{{ $kelas->nama_kelas }}</td>
            @endif
            <td>{{ $index + 1 }}</td>
            <td>{{ substr($jam, 0, 5) }}</td>
            @foreach ($hari as $h)
              <td>
                @if (in_array($jam, $jamIstirahat))
                  <div class="bg-istirahat">ISTIRAHAT</div>
                @elseif (isset($jadwalArr[$jam][$h]) && $jadwalArr[$jam][$h])
                  @php 
                    $j = $jadwalArr[$jam][$h];
                    $mapelCode = $j->mapel ? $j->mapel->nama_mapel : 'Mapel ?';
                    // Potong nama mapel jika terlalu panjang
                    if (strlen($mapelCode) > 30) {
                      $mapelCode = substr($mapelCode, 0, 27) . '...';
                    }
                    
                    $guruName = $j->guru ? $j->guru->nama : '';
                    // Potong nama guru jika terlalu panjang
                    if (strlen($guruName) > 25) {
                      $guruName = substr($guruName, 0, 22) . '...';
                    }
                  @endphp
                  <div>{{ $mapelCode }}</div>
                  <div class="guru">{{ $guruName }}</div>
                @endif
              </td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="signature">
      <div>{{ $sekolah->alamat ? trim(explode(',', $sekolah->alamat)[0]) : 'Limbangan' }}, {{ date('d F Y') }}</div>
      <div>Kepala Sekolah</div>
      <div class="signature-space"></div>
      <div>{{ $sekolah->kepala_sklh ?? '___________________________' }}</div>
      <div>NIP.</div>
    </div>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript">
      window.addEventListener('load', function() {
        // Delay printing slightly to ensure all resources are loaded
        setTimeout(function() {
          window.print();
        }, 500);
      });
    </script>
  </body>
</html>