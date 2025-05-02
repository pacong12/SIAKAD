@extends('layouts.admin.admin')

@section('title', 'Laporan Absensi Siswa')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Absensi Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('guru.absensi.index') }}">Absensi</a></div>
                <div class="breadcrumb-item active">Laporan Absensi</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Rekap Absensi Siswa</h2>
            <p class="section-lead">Lihat dan unduh data rekap absensi siswa</p>
            
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Filter Card -->
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filter Data</h4>
                    <div class="card-header-action">
                        <a data-collapse="#filter-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                    </div>
                </div>
                <div class="collapse show" id="filter-collapse">
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
                                    <i class="fas fa-search"></i> Tampilkan
                                </button>
                                
                                @if(isset($absensi) && count($absensi) > 0)
                                    <a href="{{ route('guru.absensi.cetakPdf', request()->all()) }}" target="_blank" class="btn btn-danger ml-2">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                    <a href="{{ route('guru.absensi.exportExcel', request()->all()) }}" class="btn btn-success ml-2">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistik Card -->
            @if(isset($absensi) && count($absensi) > 0)
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Hadir</h4>
                                </div>
                                <div class="card-body">
                                    {{ $summary['hadir'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-thermometer-half"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Sakit</h4>
                                </div>
                                <div class="card-body">
                                    {{ $summary['sakit'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Izin</h4>
                                </div>
                                <div class="card-body">
                                    {{ $summary['izin'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Alpa</h4>
                                </div>
                                <div class="card-body">
                                    {{ $summary['alpa'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Tabel Data -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-clipboard-list"></i> Data Absensi</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-absensi">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Tanggal</th>
                                        <th width="15%">NISN</th>
                                        <th width="25%">Nama Siswa</th>
                                        <th width="15%">Kelas</th>
                                        <th width="10%">Status</th>
                                        <th width="20%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensi as $index => $a)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>
                                            <td>{{ $a->siswa->nisn }}</td>
                                            <td>{{ $a->siswa->nama }}</td>
                                            <td>{{ $a->kelas->nama_kelas }}</td>
                                            <td>
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
                <div class="alert alert-info mt-4">
                    <div class="alert-title">Informasi</div>
                    <i class="fas fa-info-circle mr-1"></i> Tidak ada data absensi yang ditemukan untuk filter yang dipilih.
                </div>
            @else
                <div class="alert alert-light mt-4">
                    <div class="alert-title">Silahkan Pilih Filter</div>
                    <i class="fas fa-arrow-up mr-1"></i> Gunakan filter di atas untuk menampilkan data absensi siswa.
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.selectric').selectric();
        $('.select2').select2();
        
        $('#table-absensi').dataTable({
            "pageLength": 25,
            "ordering": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush 