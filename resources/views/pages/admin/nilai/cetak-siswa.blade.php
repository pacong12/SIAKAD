@extends('layouts.admin.admin')

@section('title')
    Cetak Nilai Siswa Kelas {{ $kelas->nama_kelas }}
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">Cetak Nilai Siswa Kelas {{ $kelas->nama_kelas }}</h1>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Info Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Kelas</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="120">Nama Kelas</td>
                                <td>: {{ $kelas->nama_kelas }}</td>
                            </tr>
                            <tr>
                                <td>Tingkat</td>
                                <td>: {{ $kelas->tingkat }}</td>
                            </tr>
                            <tr>
                                <td>Wali Kelas</td>
                                <td>: {{ $kelas->guru ? $kelas->guru->nama : 'Belum ditentukan' }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah Siswa</td>
                                <td>: {{ count($siswa) }} orang</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">NISN</th>
                                <th>Nama Siswa</th>
                                <th width="15%">Jenis Kelamin</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $s)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $s->nisn }}</td>
                                <td>{{ $s->nama }}</td>
                                <td>{{ $s->jns_kelamin }}</td>
                                <td>
                                    <form action="{{ route('admin.nilai.cetak-rapor') }}" method="POST" target="_blank">
                                        @csrf
                                        <input type="hidden" name="siswa_id" value="{{ $s->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-file-pdf mr-1"></i> Cetak Nilai
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('admin.nilai.cetak') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <a href="{{ route('admin.nilai.cetak-rapor-kelas', $kelas->id) }}" class="btn btn-success ml-2">
                        <i class="fas fa-file-pdf mr-1"></i> Cetak Semua Nilai
                    </a>
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
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
      <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": [[ 2, "asc" ]],
                "pageLength": 25
            });
            
            // Auto close alert after 5 seconds
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });
      </script>
@endpush 