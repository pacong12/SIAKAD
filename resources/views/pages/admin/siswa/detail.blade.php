@extends('layouts.admin.admin')

@section('title')
    Detail Siswa
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Detail Siswa {{$item->nama}}</h1>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Foto</th>
                        <td>
                            <img src="{{Storage::url($item->image)}}" alt="" style="width: 250px" class="img-thumbnail">
                        </td>
                    </tr>
                    <tr>
                        <th>Nis</th>
                        <td>{{$item->nisn}}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{$item->nama}}</td>
                    </tr>
                    <tr>
                        <th>Tempat, Tgl Lahir</th>
                        <td>{{$item->tpt_lahir}}, {{$item->tgl_lahir}}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{$item->jns_kelamin}}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>{{$item->agama}}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{$item->alamat}}</td>
                    </tr>
                    <tr>
                        <th>Orang Tua</th>
                        <td>{{$item->nama_ortu}}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>
                            @if($item->kelasAktif->isNotEmpty())
                                {{ $item->kelasAktif->first()->nama_kelas }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Asal Sekolah</th>
                        <td>{{$item->asal_sklh}}</td>
                    </tr>
                </table>

                {{-- <button type="button" class="btn btn-primary mb-3 btn-sm" data-toggle="modal" data-target="#exampleModal">
                    Tambah Nilai
                </button> --}}
                {{-- <a href="/siswa/{{$item->id}}/nilaiexport" class="btn btn-success btn-sm mb-3">Print Nilai</a>

                <div class="card shadow mb-4">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Nama Mapel</th>
                              <th>Nilai UTS</th>
                              <th>Nilai UAS</th>
                              <th>Status</th>
                              <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                              @foreach ($item->mapel as $mapel)
                                  <tr>
                                    <td>{{$mapel->nama_mapel}}</td>
                                    <td>{{$mapel->pivot->uts}}</td>
                                    <td>{{$mapel->pivot->uas}}</td>
                                    <td>{{$mapel->pivot->status}}</td>
                                    <td>
                                      <a href="/siswa/{{$item->id}}/{{$mapel->id}}/nilaitambah" class="btn btn-primary btn-sm">Tambah</a>
                                    </td>
                                  </tr>
                              @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                </div> --}}
                <a href="/admin/siswa" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
    {{-- <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Nilai</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form action="/siswa/{{$item->id}}/nilai" method="POST">
                    @csrf
                    <label for="mapel">Mapel</label>
                    <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="mapel"><i class="fas fa-book-reader"></i></span>
                    </div>
                    <select class="custom-select" name="mapel" required>
                        <option>-- Pilih Mapel --</option>
                        @foreach ($matapelajarans as $matapelajaran)
                            <option value="{{$matapelajaran->id}}">
                            {{$matapelajaran->nama_mapel}}
                            </option>
                        @endforeach
                    </select>
                    </div>
                    <div class="form-group">
                        <label for="nilai">UTS</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="nilai"><i class="far fa-id-card"></i></span>
                          </div>
                          <input type="text" class="form-control @error('nilai') is-invalid @enderror" placeholder="UTS" name="uts" value="{{old('nilai')}}">
                          @error('nilai')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                          @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nilai">UAS</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="nilai"><i class="far fa-id-card"></i></span>
                          </div>
                          <input type="text" class="form-control @error('nilai') is-invalid @enderror" placeholder="UAS" name="uas" value="{{old('nilai')}}">
                          @error('nilai')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                          @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="status"><i class="far fa-id-card"></i></span>
                          </div>
                          <input type="text" class="form-control @error('status') is-invalid @enderror" placeholder="Status" name="status" value="{{old('status')}}">
                          @error('status')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                          @enderror
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </form>
            </div>
        </div>
        </div>
    </div> --}}
@endsection


@push('prepend-style')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
@endpush

@push('addon-script')
      {{-- <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script> --}}
      <script>
        @if (Session::has('status'))
          toastr.success("{{Session::get('status')}}", "Trimakasih")
        @endif

        $.fn.editable.defaults.mode = 'inline';

        $(document).ready(function() {
            $('.uts').editable();
        });
        $(document).ready(function() {
            $('.uas').editable();
        });
        $(document).ready(function() {
            $('.status').editable();
        });
      </script>
@endpush