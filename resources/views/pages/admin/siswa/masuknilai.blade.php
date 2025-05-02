@extends('layouts.admin.admin')

@section('title')
    Nilai Siswa
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Input Nilai Siswa</h1>
        
        <!-- Info Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
            </div>
            <div class="card-body">
                <p>Silahkan pilih kelas untuk melihat daftar siswa dan menginput nilai.</p>
            </div>
        </div>
        
        <!-- Class Selection Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pilih Kelas</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse ($kelas as $item)
                        <div class="col-md-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Kelas {{ $item->nama_kelas }}</h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Tingkat {{ $item->tingkat }}
                                            @if($item->guru)
                                            <br>Wali Kelas: {{ $item->guru->nama }}
                                            @endif
                                        </small>
                                    </p>
                                    <a href="{{ url('/guru/nilaiProses/'.$item->id) }}" class="btn btn-primary">
                                        <i class="fas fa-user-graduate mr-1"></i> Lihat Siswa
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                Belum ada kelas yang tersedia. Silahkan hubungi administrator untuk menambahkan kelas.
                            </div>
                        </div>
                    @endforelse
                </div>
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
      <script>
        @if (Session::has('status'))
          toastr.success("{{Session::get('status')}}", "Trimakasih")
        @endif
      </script>
@endpush
