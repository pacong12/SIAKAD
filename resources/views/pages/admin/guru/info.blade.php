@extends('layouts.admin.admin')

@section('title')
    Info Akademik
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Info Akademik</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="events">
                    <div class="container-fluid p-0">
                        @forelse ($items as $info)
                        <div class="eventEvent mb-4" data-aos="zoom-in">
                            <div class="row">
                                <div class="col-lg-3 eventImg">
                                    <img src="{{Storage::url($info->image)}}" alt="" class="img-thumbnail" style="width: 200px; height: 220px">
                                </div>
                                <div class="col-lg-9 eventComponent align-self-center">
                                    <h3>{{$info->judul}}</h3>
                                    <div class="detailsEvents">
                                        <div class="detail">
                                            <span><i class="fa fa-calendar"></i></span>
                                            {{$info->tanggal}}
                                        </div>
                                    </div>
                                    <div class="descriptionEvents">
                                        <p>
                                            {{Str::limit($info->deskripsi, 200, '...')}}
                                        </p>
                                    </div>
                                    <a href="/guru/info/{{$info->slug}}" class="btn btn-primary">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @empty
                        <div class="alert alert-info">
                            Belum ada informasi akademik yang tersedia.
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    {{$items->links()}}
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection

@push('addon-style')
<style>
    .eventEvent {
        transition: all 0.3s ease;
    }
    .eventEvent:hover {
        transform: translateY(-5px);
    }
    .detail {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }
    .descriptionEvents p {
        color: #495057;
        margin-bottom: 15px;
    }
</style>
@endpush 