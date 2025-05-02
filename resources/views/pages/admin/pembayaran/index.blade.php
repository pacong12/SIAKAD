@extends('layouts.admin.admin')

@section('title')
    Data Pembayaran
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Pembayaran</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <form action="{{ route('pembayaran.index') }}" method="GET">
                        <div class="form-group">
                            <label for="kelas">Filter Kelas</label>
                            <select class="form-control" name="kelas" id="kelas" onchange="this.form.submit()">
                                <option value="">Semua Kelas</option>
                                <option value="1" {{ request('kelas') == '1' ? 'selected' : '' }}>Kelas 1</option>
                                <option value="2" {{ request('kelas') == '2' ? 'selected' : '' }}>Kelas 2</option>
                                <option value="3" {{ request('kelas') == '3' ? 'selected' : '' }}>Kelas 3</option>
                                <option value="4" {{ request('kelas') == '4' ? 'selected' : '' }}>Kelas 4</option>
                                <option value="5" {{ request('kelas') == '5' ? 'selected' : '' }}>Kelas 5</option>
                                <option value="6" {{ request('kelas') == '6' ? 'selected' : '' }}>Kelas 6</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('pembayaran.index') }}" method="GET">
                        <div class="form-group">
                            <label for="status">Filter Status</label>
                            <select class="form-control" name="status" id="status" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                                <option value="belum lunas" {{ request('status') == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-end h-100">
                        <a href="{{route('pembayaran.cetakexcel')}}" class="btn btn-success mr-2">
                            <i class="fas fa-file-excel mr-1"></i> Laporan Excel
                        </a>
                        <a href="{{route('pembayaran.cetakpdf')}}" class="btn btn-danger mr-2">
                            <i class="fas fa-file-pdf mr-1"></i> Laporan PDF
                        </a>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalBuatPembayaran">
                            <i class="fas fa-plus-circle mr-1"></i> Buat Pembayaran
                        </button>
                    </div>
                </div>
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
                    <th>Bukti Pembayaran</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($items as $item)
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
                        <td>{{$item->tanggal}}</td>
                        <td>
                            <span class="badge badge-{{$item->status == 'lunas' ? 'success' : 'danger'}}">
                                {{$item->status == 'lunas' ? 'Sudah Lunas' : 'Belum Lunas'}}
                            </span>
                        </td>
                        <td>
                            @if($item->bukti_pembayaran)
                                <a href="{{asset('storage/bukti_pembayaran/'.$item->bukti_pembayaran)}}" target="_blank">
                                    <img src="{{asset('storage/bukti_pembayaran/'.$item->bukti_pembayaran)}}" width="50" height="50">
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{route('pembayaran.show', $item->id)}}" class="btn btn-circle btn-sm btn-info">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{route('pembayaran.edit', $item->id)}}" class="btn btn-circle btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="{{route('pembayaran.cetakdetail', $item->id)}}" class="btn btn-circle btn-sm btn-primary">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            @if($item->status == 'belum lunas')
                                <form action="{{route('pembayaran.status', $item->id)}}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="lunas">
                                    <button type="submit" class="btn btn-circle btn-sm btn-success">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="#" class="btn btn-sm btn-circle btn-danger delete" pembayaran-id="{{$item->id}}">
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

      <!-- Modal Buat Pembayaran -->
      <div class="modal fade" id="modalBuatPembayaran" tabindex="-1" role="dialog" aria-labelledby="modalBuatPembayaranLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalBuatPembayaranLabel">Buat Pembayaran</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{ route('pembayaran.bulk') }}" method="POST" id="formBuatPembayaran">
                @csrf
                <div class="form-group">
                    <label for="jenispem_id_bulk">Jenis Pembayaran</label>
                    <select class="form-control" name="jenispem_id" id="jenispem_id_bulk" required>
                        <option value="">-- Pilih Jenis Pembayaran --</option>
                        @foreach ($jenispems as $jenis)
                            <option value="{{$jenis->id}}">
                                {{$jenis->jenis}} (Rp {{number_format($jenis->nominal, 0, ',', '.')}})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan keterangan pembayaran" required>
                </div>
                <div class="form-group">
                    <label>Tipe Pembayaran</label>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="tipe_semua" name="tipe_pembayaran" value="semua" class="custom-control-input" checked>
                        <label class="custom-control-label" for="tipe_semua">Seluruh Kelas</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="tipe_perkelas" name="tipe_pembayaran" value="perkelas" class="custom-control-input">
                        <label class="custom-control-label" for="tipe_perkelas">Per Kelas</label>
                    </div>
                </div>
                <div class="form-group" id="div_kelas" style="display: none;">
                    <label for="kelas_pembayaran">Pilih Kelas</label>
                    <select class="form-control" name="kelas_pembayaran" id="kelas_pembayaran">
                        <option value="">-- Pilih Kelas --</option>
                        <option value="1">Kelas 1</option>
                        <option value="2">Kelas 2</option>
                        <option value="3">Kelas 3</option>
                        <option value="4">Kelas 4</option>
                        <option value="5">Kelas 5</option>
                        <option value="6">Kelas 6</option>
                    </select>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              <button type="button" class="btn btn-primary" id="submitPembayaran">Buat Pembayaran</button>
            </div>
          </div>
        </div>
      </div>
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
          var table = $('#tablePembayaran').DataTable();

          // Filter Kelas
          $('#filterKelas').on('change', function() {
            table.column(3).search(this.value).draw();
          });

          // Filter Status
          $('#filterStatus').on('change', function() {
            table.column(7).search(this.value).draw();
          });

          // Filter Jenis
          $('#filterJenis').on('change', function() {
            table.column(4).search(this.value).draw();
          });

          // Toggle kelas dropdown berdasarkan tipe pembayaran
          $('input[name="tipe_pembayaran"]').on('change', function() {
            if ($(this).val() === 'perkelas') {
              $('#div_kelas').show();
              $('#kelas_pembayaran').prop('required', true);
            } else {
              $('#div_kelas').hide();
              $('#kelas_pembayaran').prop('required', false);
            }
          });

          // Submit form pembayaran massal
          $('#submitPembayaran').on('click', function() {
            var tipePembayaran = $('input[name="tipe_pembayaran"]:checked').val();
            var pesanKonfirmasi = "Akan membuat pembayaran";
            
            if (tipePembayaran === 'semua') {
              pesanKonfirmasi += " untuk seluruh kelas?";
            } else {
              var kelas = $('#kelas_pembayaran').val();
              if (!kelas) {
                swal("Error", "Silakan pilih kelas terlebih dahulu", "error");
                return;
              }
              pesanKonfirmasi += " untuk kelas " + kelas + "?";
            }

            swal({
              title: "Apakah Anda yakin?",
              text: pesanKonfirmasi,
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willSubmit) => {
              if (willSubmit) {
                $('#formBuatPembayaran').submit();
              }
            });
          });
        });
      </script>
      <script>
        $('.delete').click(function(){
          var $pembayaranid = $(this).attr('pembayaran-id');
          swal({
            title: "Apakah Kamu Yakin",
            text: "Data Pembayaran Akan Terhapus",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
              console.log(willDelete);
            if (willDelete) {
              window.location = "pembayaran/"+$pembayaranid+"/hapus";
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
      <script>
        function updateSiswaData() {
            var select = document.getElementById('selectSiswa');
            var selectedOption = select.options[select.selectedIndex];
            
            document.getElementById('nama').value = selectedOption.getAttribute('data-nama');
            document.getElementById('kelas').value = selectedOption.getAttribute('data-kelas');
        }

        // Menambahkan event listener untuk jenis pembayaran
        document.getElementById('jenispem_id') && document.getElementById('jenispem_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const nominal = selectedOption.getAttribute('data-nominal');
            const formattedNominal = new Intl.NumberFormat('id-ID').format(nominal);
            document.getElementById('jum_pemb').value = formattedNominal;
            document.getElementById('jum_pemb_hidden').value = nominal;
        });
      </script>
@endpush