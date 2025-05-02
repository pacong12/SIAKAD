@extends('layouts.admin.admin')

@section('title')
    Cetak Pembayaran
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Cetak Pembayaran</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan Pembayaran</h6>
          </div>
          <div class="card-body">
            <form id="formCetakPembayaran" onsubmit="return validateForm()">
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
                    <small class="text-danger" id="tglawalError" style="display: none;">Tanggal awal harus diisi</small>
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
                    <small class="text-danger" id="tglakhirError" style="display: none;">Tanggal akhir harus diisi</small>
                    <small class="text-danger" id="dateRangeError" style="display: none;">Tanggal akhir harus setelah tanggal awal</small>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search mr-1"></i> Tampilkan Laporan
                  </button>
                  <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                  </a>
                </div>
              </div>
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
      <script>
        function validateForm() {
          // Reset error messages
          document.getElementById('tglawalError').style.display = 'none';
          document.getElementById('tglakhirError').style.display = 'none';
          document.getElementById('dateRangeError').style.display = 'none';
          
          var tglawal = document.getElementById('tglawal').value;
          var tglakhir = document.getElementById('tglakhir').value;
          
          // Check if dates are provided
          if (tglawal === '') {
            document.getElementById('tglawalError').style.display = 'block';
            return false;
          }
          
          if (tglakhir === '') {
            document.getElementById('tglakhirError').style.display = 'block';
            return false;
          }
          
          // Check date range
          if (new Date(tglawal) > new Date(tglakhir)) {
            document.getElementById('dateRangeError').style.display = 'block';
            return false;
          }
          
          // If validation passes, redirect to the report page
          window.location.href = "{{ route('pembayaran.cetaktgl', ['tglawal' => ':tglawal', 'tglakhir' => ':tglakhir']) }}".replace(':tglawal', tglawal).replace(':tglakhir', tglakhir);
          return false; // Prevent form submission
        }
      </script>
      <script>
        @if (Session::has('status'))
          toastr.success("{{Session::get('status')}}", "Berhasil")
        @endif
      </script>
@endpush