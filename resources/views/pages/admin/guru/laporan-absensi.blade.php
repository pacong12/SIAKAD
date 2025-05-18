@extends('layouts.admin.admin')

@section('title', 'Laporan Presensi Siswa')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Presensi Siswa</h1>
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
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filter Data</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Opsi Filter:</div>
                    <a class="dropdown-item" href="{{ route('guru.absensi.laporan') }}">Reset Filter</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.absensi.laporan') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="kelas_id" class="form-control select2">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Siswa</label>
                            <select name="siswa_id" class="form-control select2">
                                <option value="">Semua Siswa</option>
                                @foreach ($siswa as $s)
                                    <option value="{{ $s->id }}" {{ request('siswa_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nisn }} - {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Periode</label>
                            <div class="input-daterange input-group">
                                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai', date('Y-m-01')) }}">
                                <div class="input-group-prepend input-group-append">
                                    <div class="input-group-text">sampai</div>
                                </div>
                                <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir', date('Y-m-t')) }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search fa-sm"></i> Tampilkan
                    </button>
                    
                    @if(isset($absensi) && count($absensi) > 0)
                        <a href="{{ route('guru.absensi.cetakPdf', request()->all()) }}" class="btn btn-danger ml-2">
                            <i class="fas fa-file-pdf fa-sm"></i> PDF
                        </a>
                        <a href="{{ route('guru.absensi.exportExcel', request()->all()) }}" class="btn btn-success ml-2">
                            <i class="fas fa-file-excel fa-sm"></i> Excel
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Cards -->
    @if(isset($absensi) && count($absensi) > 0)
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['hadir'] }}</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['sakit'] }}</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['izin'] }}</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['alpa'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Tabel Data -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clipboard-list"></i> Data Presensi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="10%">Tanggal</th>
                                <th width="15%">NISN</th>
                                <th width="25%">Nama Siswa</th>
                                <th width="15%">Kelas</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="20%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensi as $index => $a)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $a->siswa->nisn }}</td>
                                    <td>{{ $a->siswa->nama }}</td>
                                    <td>{{ $a->kelas->nama_kelas }}</td>
                                    <td class="text-center">
                                        @if($a->status == 'hadir')
                                            <span class="badge badge-success">Hadir</span>
                                        @elseif($a->status == 'sakit')
                                            <span class="badge badge-warning">Sakit</span>
                                        @elseif($a->status == 'izin')
                                            <span class="badge badge-info">Izin</span>
                                        @elseif($a->status == 'alpa')
                                            <span class="badge badge-danger">Alpa</span>
                                        @endif
                                    </td>
                                    <td>{{ $a->keterangan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif(request()->has('kelas_id') || request()->has('siswa_id') || request()->has('tanggal_mulai'))
        <div class="alert alert-info shadow-sm">
            <h5><i class="fas fa-info-circle mr-1"></i> Informasi</h5>
            <p class="mb-0">Tidak ada data Presensi yang ditemukan untuk filter yang dipilih.</p>
        </div>
    @else
        <div class="alert alert-light shadow-sm">
            <h5><i class="fas fa-arrow-up mr-1"></i> Silahkan Pilih Filter</h5>
            <p class="mb-0">Gunakan filter di atas untuk menampilkan data Presensi siswa.</p>
        </div>
    @endif
</div>
@endsection

@push('addon-script')
<script>
    $(document).ready(function() {
        $('.select2').select2();
        
        $('#dataTable').DataTable({
            "pageLength": 25,
            "ordering": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush 