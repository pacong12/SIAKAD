@extends('layouts.admin.admin')

@section('title')
    Cetak Absen Pertanggal
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Cetak Absen Pertanggal Siswa</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan Presensi</h6>
          </div>
          <div class="card-body">
            <form action="{{ route('absensiswa.cetaktgl', ['tglawal' => '_tglawal_', 'tglakhir' => '_tglakhir_']) }}" method="GET" target="_blank" id="formCetakAbsen">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tglawal">Tanggal Awal</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                      </div>
                      <input type="date" class="form-control" id="tglawal" name="tglawal" required>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tglakhir">Tanggal Akhir</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                      </div>
                      <input type="date" class="form-control" id="tglakhir" name="tglakhir" required>
                    </div>
                  </div>
                </div>
              </div>
              
              <button type="button" class="btn btn-primary" id="btnCetakLaporan">
                <i class="fas fa-print mr-2"></i>Cetak Laporan
              </button>
            </form>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
@endsection

@push('prepend-style')
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endpush

@push('addon-script')
      <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <script>
        $(document).ready(function() {
          $('#btnCetakLaporan').click(function() {
            var tglawal = $('#tglawal').val();
            var tglakhir = $('#tglakhir').val();
            
            if(tglawal == '' || tglakhir == '') {
              toastr.error('Tanggal awal dan akhir harus diisi', 'Error');
              return false;
            }
            
            // Ganti URL dengan tanggal yang diinput
            var url = "{{ route('absensiswa.cetaktgl', ['tglawal' => ':tglawal', 'tglakhir' => ':tglakhir']) }}";
            url = url.replace(':tglawal', tglawal).replace(':tglakhir', tglakhir);
            
            // Buka URL di tab baru
            window.open(url, '_blank');
          });
        });
        
        @if (Session::has('status'))
          toastr.success("{{Session::get('status')}}", "Trimakasih")
        @endif
      </script>
@endpush