@extends('layouts.admin.admin')

@section('title')
    Data Absen Siswa
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 text-gray-800">Data Absen Siswa</h1>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{route('absensiswa.cetakpdf')}}" class="btn btn-danger btn-sm px-3 py-2 mr-2">
                    <i class="fas fa-file-pdf mr-1"></i> Laporan PDF
                </a>
                <a href="{{route('absensiswa.cetakexcel')}}" class="btn btn-success btn-sm px-3 py-2 mr-2">
                    <i class="fas fa-file-excel mr-1"></i> Laporan Excel
                </a>
                <a href="{{route('absensiswa.cetakAbsen')}}" class="btn btn-primary btn-sm px-3 py-2">
                    <i class="fas fa-calendar-alt mr-1"></i> Laporan Per Tanggal
                </a>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="table-responsive">
              <table id="tableAbsen" class="table table-striped table-bordered text-center table-sm" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($items as $item)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$item->siswa->nama ?? 'Data tidak tersedia'}}</td>
                      <td>{{$item->kelas->nama_kelas ?? 'Data tidak tersedia'}}</td>
                      <td>{{$item->tanggal}}</td>
                      <td>
                        @if($item->status == 'hadir')
                          <span class="badge badge-success">Hadir</span>
                        @elseif($item->status == 'sakit')
                          <span class="badge badge-warning">Sakit</span>
                        @elseif($item->status == 'izin')
                          <span class="badge badge-info">Izin</span>
                        @elseif($item->status == 'alpa')
                          <span class="badge badge-danger">Alpa</span>
                        @else
                          <span class="badge badge-secondary">{{$item->status}}</span>
                        @endif
                      </td>
                      <td>{{$item->keterangan ?? '-'}}</td>
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
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <script>
        $(document).ready(function() {
          $('#tableAbsen').DataTable();
        } );
      </script>
      <script>
        @if (Session::has('status'))
          toastr.success("{{Session::get('status')}}", "Trimakasih")
        @endif
      </script>
@endpush