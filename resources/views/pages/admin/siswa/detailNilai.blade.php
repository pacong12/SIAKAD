@extends('layouts.admin.admin')

@section('title')
    Detail Nilai
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Detail Nilai Siswa {{$item->nama}}</h1>

        <div class="card shadow">
            <div class="card-body">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <a href="{{ route('cetaknilai.cetakaka', ['id' => $item->id, 'thnakademik' => $item->mapel->first()->pivot->thnakademik_id]) }}" class="btn btn-info btn-sm mb-2">
                            <i class="fas fa-print mr-1"></i> Print Nilai
                        </a>
                        <div class="table-responsive">
                          <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th>Tahun Akademik</th>
                                <th>Nama Mapel</th>
                                <th>Nilai UTS</th>
                                <th>Nilai UAS</th>
                                <th>Status</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->mapel as $mapel)
                                    <tr>
                                      <td>
                                          @php
                                              $thnakademik = \App\Thnakademik::find($mapel->pivot->thnakademik_id);
                                          @endphp
                                          {{ $thnakademik ? $thnakademik->tahun_akademik . ' - ' . $thnakademik->semester : '-' }}
                                      </td>
                                      <td>{{$mapel->nama_mapel}}</td>
                                      <td>{{$mapel->pivot->uts}}</td>
                                      <td>{{$mapel->pivot->uas}}</td>
                                      <td>{{$mapel->pivot->status}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                        </div>
                    </div>
                </div>
                <a href="{{ route('nilaiProses', $item->kelasAktif->first()->id ?? '') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
    
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
            $('.nilai_uh1').editable();
        });
        $(document).ready(function() {
            $('.nilai_uh2').editable();
        });
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