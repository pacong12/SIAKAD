@extends('layouts.admin.admin')

@section('title', 'Input Absensi Siswa')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Input Absensi Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('guru.absensi.index') }}">Absensi</a></div>
                <div class="breadcrumb-item active">Input Absensi</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Kelas {{ $kelasInfo->nama_kelas }}</h2>
            <p class="section-lead">Input data absensi siswa untuk kelas {{ $kelasInfo->nama_kelas }}</p>
            
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
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
            
            <!-- Tanggal Filter -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h4 class="m-0 font-weight-bold text-primary">Pilih Tanggal</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.absensi.proses', $kelasInfo->id) }}" method="GET" class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Daftar Siswa -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4>Absensi Siswa Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</h4>
                </div>
                <div class="card-body">
                    @if(count($siswa) > 0)
                        <form action="{{ route('guru.absensi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kelas_id" value="{{ $kelasInfo->id }}">
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="15%">NISN</th>
                                            <th width="25%">Nama Siswa</th>
                                            <th width="40%">Status Kehadiran</th>
                                            <th width="15%">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($siswa as $index => $s)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
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
                            
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info">
                            Tidak ada siswa yang terdaftar di kelas ini.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Statistik Absensi -->
            @if(count($siswa) > 0)
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
                                    {{ $totalAbsen['hadir'] ?? 0 }}
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
                                    {{ $totalAbsen['sakit'] ?? 0 }}
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
                                    {{ $totalAbsen['izin'] ?? 0 }}
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
                                    {{ $totalAbsen['alpa'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "pageLength": 25,
            "ordering": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush 