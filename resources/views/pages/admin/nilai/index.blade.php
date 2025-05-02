@extends('layouts.admin.admin')

@section('title')
    Nilai - Pilih Kelas
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">Input Nilai Siswa</h1>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pilih Kelas</h6>
            </div>
            <div class="card-body">
                <p class="mb-3">Silahkan pilih kelas untuk melakukan input nilai siswa.</p>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Tingkat</th>
                                <th>Wali Kelas</th>
                                <th>Jumlah Siswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelas as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_kelas }}</td>
                                <td>{{ $item->tingkat }}</td>
                                <td>{{ $item->guru ? $item->guru->nama : 'Belum ditentukan' }}</td>
                                <td>{{ $item->siswa()->wherePivot('status_aktif', true)->count() }} orang</td>
                                <td>
                                    <a href="{{ route('admin.nilai.pilih-mapel', $item->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-pen-alt mr-1"></i> Input Nilai
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada kelas yang tersedia</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection

@push('prepend-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endpush

@push('addon-script')
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
            
            // Auto close alert after 5 seconds
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });
    </script>
@endpush 