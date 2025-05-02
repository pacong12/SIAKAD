@extends('layouts.admin.admin')

@section('title')
    Detail Info Akademik
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{$item->judul}}</h1>
            <p class="d-none d-sm-inline-block text-gray-600">
                <i class="fas fa-calendar fa-sm text-gray-500"></i> {{$item->tanggal}}
            </p>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <img src="{{Storage::url($item->image)}}" alt="{{$item->judul}}" class="img-fluid img-thumbnail">
                            </div>
                            <div class="col-md-8">
                                <p class="text-justify">
                                    {{$item->deskripsi}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="/guru/info" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection

@push('addon-style')
<style>
    .img-thumbnail {
        max-height: 300px;
        object-fit: cover;
    }
</style>
@endpush 