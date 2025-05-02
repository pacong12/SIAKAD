@extends('layouts.admin.admin')

@section('title')
    Detail Kelas
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Detail Kelas: {{ $item->nama_kelas }}</h1>

        <div class="row">
            <!-- Detail Kelas -->
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Kelas</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama Kelas</th>
                                <td>{{ $item->nama_kelas }}</td>
                            </tr>
                            <tr>
                                <th>Tingkat</th>
                                <td>{{ $item->tingkat }}</td>
                            </tr>
                            <tr>
                                <th>Wali Kelas</th>
                                <td>{{ $item->wali_kelas ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tahun Akademik</th>
                                <td>
                                    @if($item->thnakademik)
                                        {{ $item->thnakademik->tahun_akademik }} - {{ $item->thnakademik->semester }}
                                        @if($item->thnakademik->status == 'aktif')
                                            <span class="badge badge-success">Aktif</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jumlah Siswa</th>
                                <td>{{ $countSiswa }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $item->deskripsi ?? '-' }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('kelas.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit Kelas</a>
                        <a href="{{ route('kelas.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
            </div>
            
            <!-- Daftar Siswa -->
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
                    </div>
                    <div class="card-body">
                        @if ($item->siswa()->wherePivot('status_aktif', true)->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tableSiswa" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NISN</th>
                                            <th>Nama</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->siswa()->wherePivot('status_aktif', true)->get() as $siswa)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $siswa->nisn }}</td>
                                                <td>{{ $siswa->nama }}</td>
                                                <td>
                                                    <a href="/admin/siswa/{{ $siswa->id }}/show" class="btn btn-circle btn-info btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Belum ada siswa yang terdaftar di kelas ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Pelajaran -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Jadwal Pelajaran</h6>
                    </div>
                    <div class="card-body">
                        @if ($item->jadwalmapel->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tableJadwal" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Hari</th>
                                            <th>Jam</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Guru</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->jadwalmapel as $jadwal)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jadwal->hari }}</td>
                                                <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                                <td>{{ $jadwal->mapel->nama_mapel ?? '-' }}</td>
                                                <td>{{ $jadwal->guru->nama ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Belum ada jadwal pelajaran untuk kelas ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection

@push('addon-script')
    <script>
        $(document).ready(function() {
            $('#tableSiswa').DataTable();
            $('#tableJadwal').DataTable();
        });
    </script>
@endpush 