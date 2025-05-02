@extends('layouts.admin.admin')

@section('title')
    Nilai - Pilih Mata Pelajaran
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">Input Nilai Kelas {{ $kelas->nama_kelas }}</h1>

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
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Kelas</h6>
                <a href="{{ route('admin.nilai.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
            <div class="card-body py-2">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <th width="150">Nama Kelas</th>
                            <td width="5">:</td>
                            <td>{{ $kelas->nama_kelas }}</td>
                            <th width="150">Tingkat</th>
                            <td width="5">:</td>
                            <td>{{ $kelas->tingkat }}</td>
                        </tr>
                        <tr>
                            <th>Wali Kelas</th>
                            <td>:</td>
                            <td>{{ $kelas->guru ? $kelas->guru->nama : 'Belum ditentukan' }}</td>
                            <th>Jumlah Siswa</th>
                            <td>:</td>
                            <td>{{ $kelas->siswa()->wherePivot('status_aktif', true)->count() }} orang</td>
                        </tr>
                        <tr>
                            <th>Tahun Akademik</th>
                            <td>:</td>
                            <td colspan="4">{{ $tahunAktif->tahun_akademik }} ({{ $tahunAktif->semester }})</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pilih Mata Pelajaran</h6>
            </div>
            <div class="card-body">
                <p class="mb-3">Silahkan pilih mata pelajaran untuk melakukan input nilai siswa kelas {{ $kelas->nama_kelas }}.</p>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode</th>
                                <th width="60%">Nama Mata Pelajaran</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mapels as $mapel)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mapel->kode_mapel }}</td>
                                <td>{{ $mapel->nama_mapel }}</td>
                                <td>
                                    <a href="{{ route('admin.nilai.input', ['kelas_id' => $kelas->id, 'mapel_id' => $mapel->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-pen-alt mr-1"></i> Input Nilai
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada mata pelajaran yang tersedia</td>
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