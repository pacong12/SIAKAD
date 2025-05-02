@extends('layouts.admin.admin')

@section('title', 'Absensi Siswa')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Absensi Siswa</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Input Absensi Siswa</h2>
            
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
                </div>
                <div class="card-body">
                    <p>Silahkan pilih kelas untuk melakukan input absensi siswa.</p>
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
                                        <a href="{{ route('guru.absensi.proses', $item->id) }}" class="btn btn-primary">
                                            <i class="fas fa-user-check mr-1"></i> Input Absensi
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
    </section>
</div>
@endsection 