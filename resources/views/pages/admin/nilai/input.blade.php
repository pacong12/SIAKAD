@extends('layouts.admin.admin')

@section('title')
    Nilai {{ $mapel->nama_mapel }} - Kelas {{ $kelas->nama_kelas }}
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">Input Nilai {{ $mapel->nama_mapel }} - Kelas {{ $kelas->nama_kelas }}</h1>

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
                <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
            </div>
            <div class="card-body py-2">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <th width="150">Nama Kelas</th>
                            <td width="5">:</td>
                            <td>{{ $kelas->nama_kelas }}</td>
                            <th width="150">Mata Pelajaran</th>
                            <td width="5">:</td>
                            <td>{{ $mapel->nama_mapel }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Akademik</th>
                            <td>:</td>
                            <td>{{ $tahunAktif->tahun_akademik }} ({{ $tahunAktif->semester }})</td>
                            <th>Jumlah Siswa</th>
                            <td>:</td>
                            <td>{{ count($siswa) }} orang</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Input Nilai</h6>
            </div>
            <div class="card-body">
                <form id="formNilai" method="POST" action="{{ route('admin.nilai.simpan') }}">
                    @csrf
                    <input type="hidden" name="mapel_id" value="{{ $mapel->id }}">
                    <input type="hidden" name="thnakademik_id" value="{{ $tahunAktif->id }}">
                    <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
            
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tableSiswa" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">NISN</th>
                                    <th width="30%">Nama Siswa</th>
                                    <th width="20%">UTS</th>
                                    <th width="20%">UAS</th>
                                    <th width="15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswa as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $s->nisn }}</td>
                                    <td>{{ $s->nama }}</td>
                                    <td>
                                        <input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
                                        <input type="number" class="form-control" name="uts[]" min="0" max="100" value="{{ $s->nilai_uts }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="uas[]" min="0" max="100" value="{{ $s->nilai_uas }}">
                                    </td>
                                    <td>
                                        <select class="form-control" name="status[]">
                                            <option value="Lulus" {{ $s->nilai_status == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                            <option value="Remedial" {{ $s->nilai_status == 'Remedial' ? 'selected' : '' }}>Remedial</option>
                                        </select>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada siswa di kelas ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Simpan Semua Nilai
                        </button>
                        <a href="{{ route('admin.nilai.pilih-mapel', $kelas->id) }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </form>
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
            $('#tableSiswa').DataTable({
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