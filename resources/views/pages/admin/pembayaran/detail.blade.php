@extends('layouts.admin.admin')

@section('title')
    Detail Pembayaran
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800 mt-4">Detail Pembayaran {{$item->nama}}</h1>

        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>NISN</th>
                                <td>{{$item->nisn}}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{$item->nama}}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>
                                    {{$item->kelas}}
                                </td>
                            </tr>
                            <tr>
                                <th>Jenis Pembayaran</th>
                                <td>
                                    @if ($item->jenispem == null)
                                        Jenis Pembayaran Terhapus
                                    @else
                                        {{$item->jenispem->jenis}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jumlah Pembayaran</th>
                                <td>Rp. {{number_format($item->jum_pemb)}}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($item->status == 'lunas')
                                        <span class="badge badge-success">Sudah Lunas</span>
                                    @elseif($item->status == 'belum lunas')
                                        <span class="badge badge-danger">Belum Lunas</span>
                                    @else
                                        <span class="badge badge-warning">{{$item->status}}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{$item->keterangan ?: '-'}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Bukti Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                @if($item->bukti_pembayaran && Storage::disk('public')->exists('bukti_pembayaran/'.$item->bukti_pembayaran))
                                    <img src="{{asset('storage/bukti_pembayaran/'.$item->bukti_pembayaran)}}" class="img-fluid" alt="Bukti Pembayaran">
                                    <div class="mt-3">
                                        <a href="{{asset('storage/bukti_pembayaran/'.$item->bukti_pembayaran)}}" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-download"></i> Download Bukti
                                        </a>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        Tidak ada bukti pembayaran
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    @if($item->status == 'belum lunas')
                        <form action="{{route('pembayaran.status', $item->id)}}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="lunas">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Tandai Sudah Lunas
                            </button>
                        </form>
                    @endif
                    <a href="{{route('pembayaran.cetakdetail', $item->id)}}" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </a>
                    <a href="{{route('pembayaran.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection