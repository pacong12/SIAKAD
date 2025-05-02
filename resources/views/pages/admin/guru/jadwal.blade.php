@extends('layouts.admin.admin')

@section('title')
    Jadwal Mengajar
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800 mt-4">
          @if (auth()->user()->guru->jns_kelamin == 'L')
              Jadwal Bapak {{auth()->user()->name}}
          @else
              Jadwal Ibu {{auth()->user()->name}}
          @endif
        </h1>

        <!-- Export Buttons -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="card shadow">
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Ekspor Jadwal</h6>
              </div>
              <div class="card-body">
                <a href="{{ url('/guru/jadwal/exportpdf/'.auth()->user()->guru->id) }}" class="btn btn-danger mr-2">
                  <i class="fa fa-file-pdf mr-1"></i> Ekspor PDF
                </a>
                <a href="{{ url('/guru/jadwal/exportexcel/'.auth()->user()->guru->id) }}" class="btn btn-success">
                  <i class="fa fa-file-excel mr-1"></i> Ekspor Excel
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($items as $item)
                    @if (auth()->user()->guru->id === $item->guru_id)
                      <tr>
                        <td>
                          @if($item->mapel)
                            {{$item->mapel->nama_mapel}}
                          @else
                            <span class="text-danger">Mapel tidak ditemukan</span>
                          @endif
                        </td>
                        <td>
                          @if($item->kelas)
                            {{$item->kelas->nama_kelas}}
                          @else
                            <span class="text-danger">Kelas tidak ditemukan</span>
                          @endif
                        </td>
                        <td>{{$item->hari}}</td>
                        <td>{{substr($item->jam_mulai, 0, 5)}}</td>
                        <td>{{substr($item->jam_selesai, 0, 5)}}</td>
                      </tr>
                    @endif
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
      <script>
        $(document).ready(function() {
          $('#dataTable').DataTable({
            "order": [[ 2, "asc" ], [ 3, "asc" ]]
          });
        } );
      </script>
      <script>
        $('.delete').click(function(){
          var $gurunama = $(this).attr('guru-nama');
          var $guruid = $(this).attr('guru-id');
          swal({
            title: "Apakah Kamu Yakin",
            text: "Data Guru "+$gurunama+" Akan Terhapus",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
              console.log(willDelete);
            if (willDelete) {
              window.location = "guru/"+$guruid+"/destroy";
            } else {
              swal("Data Guru "+$gurunama+" Tidak Terhapus");
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