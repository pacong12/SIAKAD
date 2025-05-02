@extends('layouts.admin.admin')

@section('title')
    Edit Data Siswa
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Ubah Data Siswa {{$item->nama}}</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <form action="{{ route('siswa.update', $item->id) }}" method="POST" enctype="multipart/form-data">
              @method('PUT')
              @csrf
              <div class="form-group">
                <label for="nisn">Nisn</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="nisn"><i class="far fa-id-card"></i></span>
                  </div>
                  <input type="text" class="form-control @error('nisn') is-invalid @enderror" placeholder="NISN" name="nisn" value="{{$item->nisn}}">
                  @error('nisn')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="nama">Nama</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="nama"><i class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control @error('nama') is-invalid @enderror" placeholder="Nama" name="nama" value="{{$item->nama}}">
                  @error('nama')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="tpt_lahir">Tempat Lahir</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="tpt_lahir"><i class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control @error('tpt_lahir') is-invalid @enderror" placeholder="Tempat Lahir" name="tpt_lahir" value="{{$item->tpt_lahir}}">
                  @error('tpt_lahir')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="tgl_lahir">Tanggal Lahir</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="tgl_lahir"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" placeholder="Tanggal Lahir" name="tgl_lahir" value="{{$item->tgl_lahir}}">
                  @error('tgl_lahir')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="jns_kelamin">Jenis Kelamin</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <label class="input-group-text" for="jns_kelamin"><i class="fas fa-venus-mars"></i></label>
                  </div>
                  <select class="custom-select @error('jns_kelamin') is-invalid @enderror" name="jns_kelamin" required>
                    <option disabled selected>-- Pilih --</option>
                    <option value="L" @if($item->jns_kelamin == 'L') selected @endif>Laki-Laki</option>
                    <option value="P" @if($item->jns_kelamin == 'P') selected @endif>Perempuan</option>
                  </select>
                  @error('jns_kelamin')
                    <div class="invalid-feedback">
                        Jenis kelamin harus dipilih
                    </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="agama">Agama</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <label class="input-group-text" for="agama"><i class="fas fa-heart"></i></label>
                  </div>
                  <select class="custom-select @error('agama') is-invalid @enderror" name="agama" required>
                    <option disabled selected>-- Pilih --</option>
                    <option value="Islam" @if($item->agama == 'Islam') selected @endif>Islam</option>
                    <option value="Kristen" @if($item->agama == 'Kristen') selected @endif>Kristen</option>
                    <option value="Katolik" @if($item->agama == 'Katolik') selected @endif>Katolik</option>
                    <option value="Hindu" @if($item->agama == 'Hindu') selected @endif>Hindu</option>
                    <option value="Budha" @if($item->agama == 'Budha') selected @endif>Budha</option>
                  </select>
                  @error('agama')
                    <div class="invalid-feedback">
                        Agama harus dipilih
                    </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="alamat"><i class="fas fa-map-marker-alt"></i></i></span>
                  </div>
                  <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3">{{$item->alamat}}</textarea>
                  @error('alamat')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="nama_ortu">Nama Orang Tua</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="nama_ortu"><i class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control @error('nama_ortu') is-invalid @enderror" placeholder="Nama Orang Tua" name="nama_ortu" value="{{$item->nama_ortu}}">
                  @error('nama_ortu')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="kelas_id">Kelas</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <label class="input-group-text" for="kelas_id"><i class="fas fa-user-graduate"></i></label>
                  </div>
                  <select class="custom-select" name="kelas_id">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                      <option value="{{ $k->id }}" {{ $item->kelasAktif->isNotEmpty() && $item->kelasAktif->first()->id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="asal_sklh">Asal Sekolah</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="asal_sklh"><i class="fas fa-school"></i></span>
                  </div>
                  <input type="text" class="form-control @error('asal_sklh') is-invalid @enderror" placeholder="Asal Sekolah" name="asal_sklh" value="{{$item->asal_sklh}}">
                  @error('asal_sklh')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label for="image">Foto</label>
                <input type="file" class="form-control-file" name="image">
                <p class="text-danger">Masukan Foto Bila Perlu</p>
              </div>
              <button type="submit" class="btn btn-success btn-sm">Simpan</button>
              <a href="/admin/siswa" class="btn btn-secondary btn-sm">Kembali</a>
            </form>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->
@endsection