@extends('layouts.admin.admin')

@section('title', 'Input Absensi Siswa')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Absensi Siswa</h1>
        <a href="{{ route('guru.absensi.index') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Tanggal Filter -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kelas {{ $kelasInfo->nama_kelas }} - Pilih Tanggal</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.absensi.proses', $kelasInfo->id) }}" method="GET" class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search fa-sm"></i> Tampilkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Absensi -->
    @if(count($siswa) > 0)
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAbsen['hadir'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sakit</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAbsen['sakit'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-thermometer-half fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Izin</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAbsen['izin'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-envelope fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Alpa</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAbsen['alpa'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Daftar Siswa -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Absensi Siswa Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</h6>
                </div>
                <div class="card-body">
                    @if(count($siswa) > 0)
                        <form action="{{ route('guru.absensi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kelas_id" value="{{ $kelasInfo->id }}">
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th width="15%">NISN</th>
                                            <th width="25%">Nama Siswa</th>
                                            <th width="40%">Status Kehadiran</th>
                                            <th width="15%">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($siswa as $index => $s)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $s->nisn }}</td>
                                                <td>{{ $s->nama }}</td>
                                                <td>
                                                    <input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                               name="status[{{ $s->id }}]" 
                                                               id="hadir-{{ $s->id }}" 
                                                               value="hadir" 
                                                               {{ isset($absensi[$s->id]) && $absensi[$s->id]->status == 'hadir' ? 'checked' : '' }}
                                                               {{ !isset($absensi[$s->id]) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="hadir-{{ $s->id }}">
                                                            <span class="badge badge-success">Hadir</span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                               name="status[{{ $s->id }}]" 
                                                               id="sakit-{{ $s->id }}" 
                                                               value="sakit" 
                                                               {{ isset($absensi[$s->id]) && $absensi[$s->id]->status == 'sakit' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="sakit-{{ $s->id }}">
                                                            <span class="badge badge-warning">Sakit</span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                               name="status[{{ $s->id }}]" 
                                                               id="izin-{{ $s->id }}" 
                                                               value="izin" 
                                                               {{ isset($absensi[$s->id]) && $absensi[$s->id]->status == 'izin' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="izin-{{ $s->id }}">
                                                            <span class="badge badge-info">Izin</span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                               name="status[{{ $s->id }}]" 
                                                               id="alpa-{{ $s->id }}" 
                                                               value="alpa" 
                                                               {{ isset($absensi[$s->id]) && $absensi[$s->id]->status == 'alpa' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="alpa-{{ $s->id }}">
                                                            <span class="badge badge-danger">Alpa</span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" 
                                                           name="keterangan[{{ $s->id }}]" 
                                                           value="{{ isset($absensi[$s->id]) ? $absensi[$s->id]->keterangan : '' }}" 
                                                           placeholder="Keterangan">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save fa-sm text-white-50"></i> Simpan Absensi
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info">
                            Tidak ada siswa yang terdaftar di kelas ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "pageLength": 25,
            "ordering": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [3, 4] }
            ]
        });
    });
</script>
@endpush 