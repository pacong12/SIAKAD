@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800 mt-3">Jadwal Mata Pelajaran</h1>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Mapel</th>
                            <th>Nama Guru</th>
                            <th>Kelas</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
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
                                    @if($item->kelas)
                                        {{$item->kelas->nama_kelas}}
                                    @else
                                        {{$item->kelas_id ?? 'Tidak ada'}}
                                    @endif
                                </td>
                                <td>{{$item->hari}}</td>
                                <td>{{$item->jam_mulai}}</td>
                                <td>{{$item->jam_selesai}}</td>
                            </tr>
                        @endforeach
                        {{-- @forelse (auth()->user()->siswa->mapel as $mapel)
                            <tr>
                                <td>{{$mapel->nama_mapel}}</td>
                            </tr>
                        @empty
                            
                        @endforelse --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
      <!-- /.container-fluid -->
@endsection