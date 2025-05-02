@extends('layouts.admin.admin')

@section('title')
    Edit Data Jadwal Mata Pelajaran
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Ubah Jadwal</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <form action="/admin/jadwalmapel/{{$item->id}}/update" method="POST">
                @method('PUT')
                @csrf
                <label for="guru_id">Guru</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="guru_id"><i class="fas fa-user"></i></span>
                  </div>
                  <select name="guru_id" required class="custom-select">
                    <option value="">-- Pilih Guru --</option>
                    @foreach ($gurus as $guru)
                        <option value="{{$guru->id}}" {{ $item->guru_id == $guru->id ? 'selected' : '' }}>
                            {{$guru->nama}}
                        </option>
                    @endforeach
                  </select>
                </div>
                <label for="mapel_id">Mapel</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="mapel_id"><i class="fas fa-book-reader"></i></span>
                  </div>
                  <select class="custom-select" name="mapel_id" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach ($mapels as $mapel)
                        <option value="{{$mapel->id}}" {{ $item->mapel_id == $mapel->id ? 'selected' : '' }}>
                          {{$mapel->nama_mapel}}
                        </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="kelas_id">Kelas</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <label class="input-group-text" for="kelas_id"><i class="fas fa-user-graduate"></i></label>
                    </div>
                    <select class="custom-select" name="kelas_id" required>
                      <option value="">-- Pilih Kelas --</option>
                      @foreach ($kelas as $k)
                          <option value="{{$k->id}}" {{ $item->kelas_id == $k->id ? 'selected' : '' }}>
                            {{$k->nama_kelas}}
                          </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <label for="hari">Hari</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="hari"><i class="fas fa-cloud-sun"></i></span>
                  </div>
                  <select class="custom-select" name="hari" required>
                    <option value="">-- Pilih Hari --</option>
                    <option value="Senin" @if($item->hari == 'Senin') selected @endif>Senin</option>
                    <option value="Selasa" @if($item->hari == 'Selasa') selected @endif>Selasa</option>
                    <option value="Rabu" @if($item->hari == 'Rabu') selected @endif>Rabu</option>
                    <option value="Kamis" @if($item->hari == 'Kamis') selected @endif>Kamis</option>
                    <option value="Jumat" @if($item->hari == 'Jumat') selected @endif>Jumat</option>
                    <option value="Sabtu" @if($item->hari == 'Sabtu') selected @endif>Sabtu</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="jam_mulai">Jam Mulai</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="jam_mulai"><i class="far fa-clock"></i></span>
                    </div>
                    <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" placeholder="Jam Mulai" name="jam_mulai" value="{{$item->jam_mulai}}" required>
                    @error('jam_mulai')
                      <div class="invalid-feedback">
                          {{$message}}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="form-group">
                  <label for="jam_selesai">Jam Selesai</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="jam_selesai"><i class="far fa-clock"></i></span>
                    </div>
                    <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" placeholder="Jam Selesai" name="jam_selesai" value="{{$item->jam_selesai}}" required>
                    @error('jam_selesai')
                      <div class="invalid-feedback">
                          {{$message}}
                      </div>
                    @enderror
                  </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                <a href="/admin/jadwalmapel" class="btn btn-secondary btn-sm">Kembali</a>
            </form>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->
@endsection