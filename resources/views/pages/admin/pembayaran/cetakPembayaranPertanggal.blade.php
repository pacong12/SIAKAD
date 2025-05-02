@extends('layouts.admin.admin')

@section('title')
    Cetak Pembayaran
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Laporan Pembayaran</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              Laporan Pembayaran Periode: {{ date('d M Y', strtotime(request('tglawal'))) }} - {{ date('d M Y', strtotime(request('tglakhir'))) }}
            </h6>
          </div>
          <div class="card-body">
            <div class="row mb-4">
              <div class="col-md-12">
                <form action="{{ route('pembayaran.cetaktgl', [request('tglawal'), request('tglakhir')]) }}" method="GET" class="mb-3">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="kelas">Filter Kelas</label>
                        <select class="form-control" name="kelas" id="kelas">
                          <option value="">Semua Kelas</option>
                          <option value="1" {{ request('kelas') == '1' ? 'selected' : '' }}>Kelas 1</option>
                          <option value="2" {{ request('kelas') == '2' ? 'selected' : '' }}>Kelas 2</option>
                          <option value="3" {{ request('kelas') == '3' ? 'selected' : '' }}>Kelas 3</option>
                          <option value="4" {{ request('kelas') == '4' ? 'selected' : '' }}>Kelas 4</option>
                          <option value="5" {{ request('kelas') == '5' ? 'selected' : '' }}>Kelas 5</option>
                          <option value="6" {{ request('kelas') == '6' ? 'selected' : '' }}>Kelas 6</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="status">Status Pembayaran</label>
                        <select class="form-control" name="status" id="status">
                          <option value="">Semua Status</option>
                          <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                          <option value="belum lunas" {{ request('status') == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter mr-1"></i> Filter
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            
            <div class="mb-3">
              <a href="{{ route('pembayaran.cetak') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <a href="{{ request('kelas') || request('status') ? 
                route('pembayaran.cetakpdf', ['tglawal' => request('tglawal'), 'tglakhir' => request('tglakhir'), 'kelas' => request('kelas'), 'status' => request('status')]) : 
                route('pembayaran.cetakpdf', ['tglawal' => request('tglawal'), 'tglakhir' => request('tglakhir')]) }}" 
                class="btn btn-danger">
                <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
              </a>
            </div>
            
            <div class="table-responsive">
              <table class="table table-striped table-sm table-bordered text-center" id="tablePembayaran" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jenis Pembayaran</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($pembayaranPertanggal as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->nisn}}</td>
                        <td>{{$item->nama}}</td>
                        <td>{{$item->kelas}}</td>
                        <td>
                          @if ($item->jenispem == null)
                              Jenis Pembayaran Terhapus
                          @else
                              {{$item->jenispem->jenis}}
                          @endif
                        </td>
                        <td>Rp. {{number_format($item->jum_pemb)}}</td>
                        <td>{{date('d M Y', strtotime($item->tanggal))}}</td>
                        <td>
                          <span class="badge badge-{{$item->status == 'lunas' ? 'success' : 'danger'}}">
                            {{$item->status == 'lunas' ? 'Sudah Lunas' : 'Belum Lunas'}}
                          </span>
                        </td>
                        <td>
                            <a href="{{route('pembayaran.show', $item->id)}}" class="btn btn-circle btn-sm btn-info">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{route('pembayaran.cetakdetail', $item->id)}}" class="btn btn-circle btn-sm btn-primary">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" class="text-center">Tidak ada data pembayaran pada periode ini</td>
                    </tr>
                  @endforelse
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
          $('#tablePembayaran').DataTable();
        } );
      </script>
      <script>
        @if (Session::has('status'))
          toastr.success("{{Session::get('status')}}", "Trimakasih")
        @endif
      </script>
@endpush