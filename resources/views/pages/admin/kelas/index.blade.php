@extends('layouts.admin.admin')

@section('title')
    Data Kelas
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 text-gray-800">Data Kelas</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <a href="{{ route('kelas.create') }}" class="btn btn-primary btn-sm mb-3 px-3 py-2">
              <i class="fas fa-plus mr-2"></i>
              Tambah Kelas
            </a>
            <div class="table-responsive">
              <table id="tableKelas" class="table table-striped table-bordered text-center table-sm" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Wali Kelas</th>
                    <th>Tahun Akademik</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($items as $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $item->nama_kelas }}</td>
                      <td>{{ $item->tingkat }}</td>
                      <td>{{ $item->wali_kelas ?? '-' }}</td>
                      <td>
                        @if($item->thnakademik)
                            {{ $item->thnakademik->tahun_akademik }} - {{ $item->thnakademik->semester }}
                            @if($item->thnakademik->status == 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @endif
                        @else
                            -
                        @endif
                      </td>
                      <td>
                        <a href="{{ route('kelas.show', $item->id) }}" class="btn btn-circle btn-info btn-sm">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ route('kelas.edit', $item->id) }}" class="btn btn-circle btn-warning btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="#" class="btn btn-circle btn-danger btn-sm delete" kelas-nama="{{ $item->nama_kelas }}" kelas-id="{{ $item->id }}">
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
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <script>
        $(document).ready(function() {
          $('#tableKelas').DataTable();
        } );
      </script>
      <script>
        $('.delete').click(function(){
          var $kelasnama = $(this).attr('kelas-nama');
          var $kelasid = $(this).attr('kelas-id');
          swal({
            title: "Apakah Kamu Yakin",
            text: "Data Kelas "+$kelasnama+" Akan Terhapus",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              // Buat form untuk metode DELETE dan submit
              var form = document.createElement('form');
              form.action = "{{ url('/admin/kelas') }}/"+$kelasid;
              form.method = 'POST';
              form.style.display = 'none';
              
              var csrfToken = document.createElement('input');
              csrfToken.type = 'hidden';
              csrfToken.name = '_token';
              csrfToken.value = "{{ csrf_token() }}";
              
              var methodField = document.createElement('input');
              methodField.type = 'hidden';
              methodField.name = '_method';
              methodField.value = 'DELETE';
              
              form.appendChild(csrfToken);
              form.appendChild(methodField);
              document.body.appendChild(form);
              
              form.submit();
            } else {
              swal("Data Kelas "+$kelasnama+" Tidak Terhapus");
            }
          });
        });
      </script>
      <script>
        @if (Session::has('status'))
          toastr.success("{{Session::get('status')}}", "Berhasil")
        @endif
        
        @if (Session::has('error'))
          toastr.error("{{Session::get('error')}}", "Gagal")
        @endif
      </script>
@endpush