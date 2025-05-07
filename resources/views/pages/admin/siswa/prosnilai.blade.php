@extends('layouts.admin.admin')

@section('title')
    Nilai Siswa Kelas {{ $kelas->nama_kelas }}
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">Daftar Siswa {{ $kelas->nama_kelas }}</h1>
        
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
                                <td>Nama Kelas</td>
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
                                <td>: {{ count($pnilai) }} orang</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mata Pelajaran dan Tahun Akademik -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pilih Mata Pelajaran</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mapelSelect">Mata Pelajaran</label>
                            <select class="form-control" id="mapelSelect">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach (App\Mapel::all() as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tahun Akademik Aktif</label>
                            <input type="text" class="form-control" value="{{ $tahunAktif->tahun_akademik }} - {{ $tahunAktif->semester }}" readonly>
                            <small class="text-info">Menggunakan tahun akademik yang aktif di sistem</small>
                        </div>
                    </div>
                </div>
                <button id="showFormBtn" class="btn btn-primary mt-3">
                    <i class="fas fa-clipboard-list mr-1"></i> Tampilkan Form Nilai
                </button>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4" id="nilaiFormCard" style="display: none;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Input Nilai <span id="mapelTitle"></span> - Tahun Akademik {{ $tahunAktif->tahun_akademik }} ({{ $tahunAktif->semester }})</h6>
            </div>
          <div class="card-body">
                <form id="formNilai" method="POST" action="{{ route('nilai.simpan.batch') }}">
                    @csrf
                    <input type="hidden" name="mapel_id" id="mapelId">
                    <input type="hidden" name="thnakademik_id" id="tahunAkademikId">
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
                                @forelse ($pnilai as $siswa)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->nisn }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>
                                        <input type="hidden" name="siswa_id[]" value="{{ $siswa->id }}">
                                        <input type="number" class="form-control" name="uts[]" min="0" max="100">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="uas[]" min="0" max="100">
                                    </td>
                                    <td>
                                        <select class="form-control" name="status[]">
                                            <option value="Lulus">Lulus</option>
                                            <option value="Remedial">Remedial</option>
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
                        <a href="/guru/nilai" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kartu Daftar Siswa (Default view) -->
        <div class="card shadow mb-4" id="defaultCard">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tableSiswaDefault" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pnilai as $siswa)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $siswa->nisn }}</td>
                                <td>{{ $siswa->nama }}</td>
                                <td>
                                    <a href="{{ route('siswa.nilai.detail', $siswa->id) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye mr-1"></i> Lihat Nilai
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada siswa di kelas ini</td>
                            </tr>
                            @endforelse
                </tbody>
              </table>
            </div>
                <a href="/guru/nilai" class="btn btn-secondary mt-3">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
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
            $('#tableSiswaDefault').DataTable();
            
            // Form validation and display
            $('#mapelSelect').change(function() {
                if ($('#mapelSelect').val()) {
                    $('#showFormBtn').prop('disabled', false);
                } else {
                    $('#showFormBtn').prop('disabled', true);
                }
            });
            
            // Set default state disabled
            $('#showFormBtn').prop('disabled', true);
            
            $('#showFormBtn').click(function() {
                var mapelId = $('#mapelSelect').val();
                var mapelText = $('#mapelSelect option:selected').text();
                var kelasId = {{ $kelas->id }};
                var tahunAkademikId = {{ $tahunAktif->id }};
                
                $('#mapelId').val(mapelId);
                $('#tahunAkademikId').val(tahunAkademikId);
                $('#mapelTitle').text(mapelText);
                
                // Tampilkan form dan sembunyikan tabel default
                $('#defaultCard').hide();
                $('#nilaiFormCard').show();
                
                // Ambil data nilai siswa dari server menggunakan AJAX
                $.ajax({
                    url: "{{ route('nilai.get-siswa') }}",
                    type: "GET",
                    data: {
                        mapel_id: mapelId,
                        kelas_id: kelasId,
                        thnakademik_id: tahunAkademikId
                    },
                    dataType: "json",
                    success: function(data) {
                        // Iterasi setiap siswa dan isi form dengan nilai yang ada
                        $.each(data, function(index, siswa) {
                            var row = $('input[name="siswa_id[]"][value="' + siswa.id + '"]').closest('tr');
                            
                            if (siswa.nilai) {
                                // Jika ada nilai, isi form dengan nilai yang ada
                                row.find('input[name="uts[]"]').val(siswa.nilai.uts);
                                row.find('input[name="uas[]"]').val(siswa.nilai.uas);
                                row.find('select[name="status[]"]').val(siswa.nilai.status);
                            } else {
                                // Jika tidak ada nilai, kosongkan form
                                row.find('input[name="uts[]"]').val('');
                                row.find('input[name="uas[]"]').val('');
                                row.find('select[name="status[]"]').val('Lulus');
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan saat mengambil data nilai");
                    }
                });
            });
        });
      </script>
@endpush