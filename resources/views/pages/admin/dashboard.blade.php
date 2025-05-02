@extends('layouts.admin.admin')

@section('title')
    Dashboard
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

      <!-- Page Heading -->
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
      </div>

      @if (auth()->user()->role === 'admin')
      <!-- Admin Dashboard -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-left-primary shadow py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="h5 font-weight-bold text-primary text-uppercase mb-1">Selamat Datang, Admin!</div>
                  <div class="text-gray-800">Anda login sebagai Administrator SIAKAD</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Admin Stats Cards -->
      <div class="row">
        <!-- Data Siswa Card -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Data Siswa</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{$siswa}}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-users fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Data Guru Card -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Data Guru</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{$guru}}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Mata Pelajaran Card -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Mata Pelajaran</div>
                  <div class="row no-gutters align-items-center">
                    <div class="col-auto">
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$mapel}}</div>
                    </div>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-book fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Kelas Card -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kelas</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kelas ?? 0 }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-home fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Admin Quick Access -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Akses Cepat</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                  <a href="/admin/siswa" class="btn btn-primary btn-icon-split btn-lg w-100">
                    <span class="icon text-white-50">
                      <i class="fas fa-users"></i>
                    </span>
                    <span class="text">Kelola Siswa</span>
                  </a>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                  <a href="/admin/guru" class="btn btn-success btn-icon-split btn-lg w-100">
                    <span class="icon text-white-50">
                      <i class="fas fa-chalkboard-teacher"></i>
                    </span>
                    <span class="text">Kelola Guru</span>
                  </a>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                  <a href="/admin/jadwalmapel" class="btn btn-info btn-icon-split btn-lg w-100">
                    <span class="icon text-white-50">
                      <i class="fas fa-calendar-alt"></i>
                    </span>
                    <span class="text">Jadwal</span>
                  </a>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                  <a href="{{ route('admin.nilai.index') }}" class="btn btn-warning btn-icon-split btn-lg w-100">
                    <span class="icon text-white-50">
                      <i class="fas fa-graduation-cap"></i>
                    </span>
                    <span class="text">Nilai</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      @elseif (auth()->user()->role === 'guru')
      <!-- Guru Dashboard -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-left-success shadow py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="h5 font-weight-bold text-success text-uppercase mb-1">Selamat Datang, Guru!</div>
                  <div class="text-gray-800">Anda login sebagai Guru SIAKAD</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Guru Stats Cards -->
      <div class="row">
        <!-- Mata Pelajaran Card -->
        <div class="col-xl-4 col-md-6 mb-4">
          <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jadwal Pelajaran</div>
                  <div class="row no-gutters align-items-center">
                    <div class="col-auto">
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $jadwal ?? 0 }}</div>
                    </div>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Kelas Diampu Card -->
        <div class="col-xl-4 col-md-6 mb-4">
          <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kelas Diampu</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kelasGuru ?? 0 }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-home fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Siswa Card -->
        <div class="col-xl-4 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $siswa }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-users fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Guru Quick Access -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-success">Akses Cepat</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                  <a href="/guru/jadwal" class="btn btn-primary btn-icon-split btn-lg w-100">
                    <span class="icon text-white-50">
                      <i class="fas fa-calendar-alt"></i>
                    </span>
                    <span class="text">Jadwal</span>
                  </a>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                  <a href="/guru/nilai" class="btn btn-success btn-icon-split btn-lg w-100">
                    <span class="icon text-white-50">
                      <i class="fas fa-graduation-cap"></i>
                    </span>
                    <span class="text">Nilai</span>
                  </a>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                  <a href="{{ route('guru.absensi.index') }}" class="btn btn-info btn-icon-split btn-lg w-100">
                    <span class="icon text-white-50">
                      <i class="fas fa-user-check"></i>
                    </span>
                    <span class="text">Absensi</span>
                  </a>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                  <a href="/guru/profile" class="btn btn-warning btn-icon-split btn-lg w-100">
                    <span class="icon text-white-50">
                      <i class="fas fa-user"></i>
                    </span>
                    <span class="text">Profile</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
    <!-- /.container-fluid -->
@endsection
