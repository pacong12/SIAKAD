@extends('layouts.admin.admin')

@section('title')
    Edit Kelas
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Edit Kelas: {{ $item->nama_kelas }}</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <form action="{{ route('kelas.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="tingkat">Tingkat</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                        </div>
                        <select class="form-control @error('tingkat') is-invalid @enderror" name="tingkat" id="tingkat">
                            <option value="">-- Pilih Tingkat --</option>
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('tingkat', $item->tingkat) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('tingkat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="nama_kelas">Nama Kelas</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-school"></i></span>
                        </div>
                        <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" name="nama_kelas" id="nama_kelas" placeholder="Nama kelas akan terisi otomatis" value="{{ old('nama_kelas', $item->nama_kelas) }}" readonly>
                        @error('nama_kelas')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <small class="text-muted">Nama kelas akan otomatis terisi berdasarkan tingkat yang dipilih</small>
                </div>
                
                <div class="form-group">
                    <label for="guru_id">Wali Kelas</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        </div>
                        <select class="form-control @error('guru_id') is-invalid @enderror" name="guru_id">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('guru_id', $item->guru_id) == $guru->id ? 'selected' : '' }}>{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="thnakademik_id">Tahun Akademik</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <select class="form-control @error('thnakademik_id') is-invalid @enderror" name="thnakademik_id">
                            <option value="">-- Pilih Tahun Akademik --</option>
                            @foreach ($thnakademiks as $thnakademik)
                                <option value="{{ $thnakademik->id }}" {{ old('thnakademik_id', $item->thnakademik_id) == $thnakademik->id ? 'selected' : '' }}>
                                    {{ $thnakademik->tahun_akademik }} - {{ $thnakademik->semester }} {{ $thnakademik->status == 'aktif' ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('thnakademik_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi" rows="3" placeholder="Deskripsi kelas (opsional)">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success btn-sm">Update</button>
                <a href="{{ route('kelas.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </form>
          </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk mengupdate nama kelas berdasarkan tingkat
        function updateNamaKelas() {
            const tingkat = document.getElementById('tingkat').value;
            const namaKelasInput = document.getElementById('nama_kelas');
            
            if (tingkat) {
                namaKelasInput.value = 'Kelas ' + tingkat;
            } else {
                namaKelasInput.value = '';
            }
        }
        
        // Event listener untuk perubahan tingkat
        document.getElementById('tingkat').addEventListener('change', updateNamaKelas);
        
        // Tidak perlu panggil pada load halaman karena sudah ada nilai dari database
    });
</script>
@endpush 