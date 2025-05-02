@extends('layouts.admin.admin')

@section('title')
    Data Jadwal Mata Pelajaran
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 text-gray-800">Jadwal Mata Pelajaran</h1>
        
        <!-- Filter dan Tombol Cetak -->
        <div class="row mb-4">
          <div class="col-md-8">
            <div class="card shadow">
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Filter & Cetak Jadwal</h6>
              </div>
              <div class="card-body">
                <form id="filter-form" class="form-inline">
                  <div class="form-group mr-3">
                    <label for="kelas_filter" class="mr-2">Kelas:</label>
                    <select class="form-control" id="kelas_filter">
                      <option value="">Semua Kelas</option>
                      @foreach($items->groupBy('kelas_id') as $kelasId => $jadwalKelas)
                        @if($jadwalKelas[0]->kelas && $kelasId)
                        <option value="{{ $kelasId }}">{{ $jadwalKelas[0]->kelas->nama_kelas }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <button type="button" class="btn btn-danger mr-2" id="cetak-pdf">
                    <i class="fa fa-file-pdf mr-1"></i> Cetak PDF
                  </button>
                  <button type="button" class="btn btn-success" id="cetak-excel">
                    <i class="fa fa-file-excel mr-1"></i> Cetak Excel
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <a href="/admin/jadwalmapel/create" class="btn btn-primary btn-sm mb-3 px-3 py-2">
              <i class="fas fa-plus mr-2"></i>
              Tambah Jadwal
            </a>
            <div class="table-responsive">
              <table class="table table-striped table-sm table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kelas</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mapel</th>
                    <th>Guru</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($items as $item)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>
                        @if($item->kelas)
                          {{$item->kelas->nama_kelas}}
                        @else
                          <span class="text-danger">Kelas tidak ditemukan</span>
                        @endif
                      </td>
                      <td>{{$item->hari}}</td>
                      <td>{{substr($item->jam_mulai, 0, 5)}} - {{substr($item->jam_selesai, 0, 5)}}</td>
                      <td>
                        @if($item->mapel)
                          {{$item->mapel->nama_mapel}}
                        @else
                          <span class="text-danger">Mapel tidak ditemukan</span>
                        @endif
                      </td>
                      <td>
                        @if($item->guru)
                          {{$item->guru->nama}}
                        @else
                          <span class="text-danger">Guru tidak ditemukan</span>
                        @endif
                      </td>
                      <td>
                          <a href="/admin/jadwalmapel/{{$item->id}}/edit" class="btn btn-circle btn-sm btn-warning">
                              <i class="fa fa-edit"></i>
                          </a>
                          <a href="#" class="btn btn-sm btn-circle btn-danger delete" jmapel-id="{{$item->id}}">
                              <i class="fa fa-trash"></i>
                          </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
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
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script>
        $(document).ready(function() {
          // DataTable initialization
          $('#dataTable').DataTable({
            "order": [[ 1, "asc" ], [ 2, "asc" ], [ 3, "asc" ]]
          });
          
          // Handle cetak PDF button
          $('#cetak-pdf').click(function() {
            var kelasId = $('#kelas_filter').val();
            if (kelasId) {
              window.location.href = '/admin/jadwalmapel/exportpdf/' + kelasId;
            } else {
              window.location.href = '/admin/jadwalmapel/exportpdf';
            }
          });
          
          // Handle cetak Excel button
          $('#cetak-excel').click(function() {
            var kelasId = $('#kelas_filter').val();
            if (kelasId) {
              window.location.href = '/admin/jadwalmapel/exportexcel/' + kelasId;
            } else {
              window.location.href = '/admin/jadwalmapel/exportexcel';
            }
          });
        });
        
        $('.delete').click(function(){
          var $jmapelid = $(this).attr('jmapel-id');
          swal({
            title: "Apakah Kamu Yakin",
            text: "Data Jadwal Mapel Akan Terhapus",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
              console.log(willDelete);
            if (willDelete) {
              window.location = "/admin/jadwalmapel/"+$jmapelid+"/destroy";
            } else {
              swal("Data Tidak Terhapus");
            }
          });
        })
      </script>
      <script>
        @if (Session::has('status'))
          toastr.success("{{Session::get('status')}}", "Trimakasih")
        @endif
      </script>
@endpush