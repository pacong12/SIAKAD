<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
      <img src="{{url('../../foto/tutwuri.png')}}" width="40px" alt="">
      <div class="sidebar-brand-text mx-3">SIAKAD</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    @if (auth()->user()->role === 'admin')
      <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('dashboard.admin')}}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
    @endif

    @if (auth()->user()->role === 'guru')
      <li class="nav-item {{ request()->is('guru') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('dashboard.guru')}}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    @if(auth()->user()->role === 'admin')
    <!-- Heading -->
    <div class="sidebar-heading">
        Master Data
    </div>
    
    <!-- Nav Item - Master Data Collapse Menu -->
    <li class="nav-item {{ request()->is('admin/siswa*', 'admin/guru*', 'admin/jadwalmapel*', 'admin/kelas*', 'admin/mapel*', 'admin/thnakademik*', 'admin/sekolah*', 'admin/user*', 'admin/info*') && !request()->is('admin/nilai*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->is('admin/siswa*', 'admin/guru*', 'admin/jadwalmapel*', 'admin/kelas*', 'admin/mapel*', 'admin/thnakademik*', 'admin/sekolah*', 'admin/user*', 'admin/info*') && !request()->is('admin/nilai*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="{{ request()->is('admin/siswa*', 'admin/guru*', 'admin/jadwalmapel*', 'admin/kelas*', 'admin/mapel*', 'admin/thnakademik*', 'admin/sekolah*', 'admin/user*', 'admin/info*') && !request()->is('admin/nilai*') ? 'true' : 'false' }}" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Data Master</span>
        </a>
        <div id="collapsePages" class="collapse {{ request()->is('admin/siswa*', 'admin/guru*', 'admin/jadwalmapel*', 'admin/kelas*', 'admin/mapel*', 'admin/thnakademik*', 'admin/sekolah*', 'admin/user*', 'admin/info*') && !request()->is('admin/nilai*') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item {{ request()->is('admin/siswa*') && !request()->is('admin/nilai*') ? 'active' : '' }}" href="/admin/siswa">Data Siswa</a>
            <a class="collapse-item {{ request()->is('admin/guru*') && !request()->is('admin/nilai*') ? 'active' : '' }}" href="/admin/guru">Data Guru</a>
            <a class="collapse-item {{ request()->is('admin/jadwalmapel*') ? 'active' : '' }}" href="/admin/jadwalmapel">Jadwal Mapel</a>
            <a class="collapse-item {{ request()->is('admin/kelas*') && !request()->is('admin/nilai*') ? 'active' : '' }}" href="{{ route('kelas.index') }}">Data Kelas</a>
            <a class="collapse-item {{ request()->is('admin/mapel*') ? 'active' : '' }}" href="{{route('mapel.index')}}">Data Mapel</a>
            <a class="collapse-item {{ request()->is('admin/thnakademik*') ? 'active' : '' }}" href="{{route('thnakademik.index')}}">Data Tahun Akademik</a>
            <a class="collapse-item {{ request()->is('admin/sekolah*') ? 'active' : '' }}" href="{{route('sekolah.index')}}">Data Sekolah</a>
          </div>
        </div>
    </li>
    
    <!-- Heading -->
    <div class="sidebar-heading">
        Akademik
    </div>
    
    <!-- Nav Item - Nilai Collapse Menu -->
    <li class="nav-item {{ request()->is('admin/nilai*') ? 'active' : '' }}">
      <a class="nav-link {{ request()->is('admin/nilai*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseNilai" aria-expanded="{{ request()->is('admin/nilai*') ? 'true' : 'false' }}" aria-controls="collapseNilai">
        <i class="fas fa-fw fa-graduation-cap"></i>
        <span>Nilai</span>
      </a>
      <div id="collapseNilai" class="collapse {{ request()->is('admin/nilai*') ? 'show' : '' }}" aria-labelledby="headingNilai" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item {{ request()->is('admin/nilai') || request()->is('admin/nilai/kelas*') && !request()->is('admin/nilai/cetak*') ? 'active' : '' }}" href="{{route('admin.nilai.index')}}">Input Nilai</a>
          <a class="collapse-item {{ request()->is('admin/nilai/cetak*') ? 'active' : '' }}" href="{{route('admin.nilai.cetak')}}">Cetak Nilai</a>
        </div>
      </div>
    </li>
    
    <!-- Nav Item - Pembayaran Collapse Menu -->
    <li class="nav-item {{ request()->is('admin/jenispem*', 'admin/pembayaran*') ? 'active' : '' }}">
      <a class="nav-link {{ request()->is('admin/jenispem*', 'admin/pembayaran*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="{{ request()->is('admin/jenispem*', 'admin/pembayaran*') ? 'true' : 'false' }}" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-money-bill-wave"></i>
        <span>Pembayaran</span>
      </a>
      <div id="collapseTwo" class="collapse {{ request()->is('admin/jenispem*', 'admin/pembayaran*') ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item {{ request()->is('admin/jenispem*') ? 'active' : '' }}" href="{{route('jenispem.index')}}">Jenis Pembayaran</a>
          <a class="collapse-item {{ request()->is('admin/pembayaran') || request()->is('admin/pembayaran/create') ? 'active' : '' }}" href="{{route('pembayaran.index')}}">Pembayaran</a>
          <a class="collapse-item {{ request()->is('admin/cetakPembayaran*') ? 'active' : '' }}" href="{{route('pembayaran.cetak')}}">Cetak Pertanggal</a>
        </div>
      </div>
    </li>
    
    <!-- Nav Item - Absen Collapse Menu -->
    <li class="nav-item {{ request()->is('admin/absensiswa*', 'admin/cetakAbsen*') ? 'active' : '' }}">
      <a class="nav-link {{ request()->is('admin/absensiswa*', 'admin/cetakAbsen*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="{{ request()->is('admin/absensiswa*', 'admin/cetakAbsen*') ? 'true' : 'false' }}" aria-controls="collapseThree">
        <i class="fas fa-fw fa-calendar-check"></i>
        <span>Presensi</span>
      </a>
      <div id="collapseThree" class="collapse {{ request()->is('admin/absensiswa*', 'admin/cetakAbsen*') ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item {{ request()->is('admin/absensiswa') ? 'active' : '' }}" href="{{route('absensiswa.index')}}">Presensi Siswa</a>
          <a class="collapse-item {{ request()->is('admin/cetakAbsenSiswa*') ? 'active' : '' }}" href="{{route('absensiswa.cetakAbsen')}}">Cetak Presensi</a>
        </div>
      </div>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">
    
    <!-- Heading -->
    <div class="sidebar-heading">
        Lainnya
    </div>
    
    <li class="nav-item {{ request()->is('admin/info') ? 'active' : '' }}">
      <a class="nav-link" href="{{route('info.index')}}">
        <i class="fas fa-fw fa-newspaper"></i>
        <span>Info</span></a>
    </li>
    
    <li class="nav-item {{ request()->is('admin/user') ? 'active' : '' }}">
      <a class="nav-link" href="/admin/user">
        <i class="fas fa-fw fa-user"></i>
        <span>User</span></a>
    </li>
    @endif

    @if (auth()->user()->role === 'guru')
    <!-- Divider -->
    
    <!-- Heading -->
    <div class="sidebar-heading">
        Guru
    </div>
    
      <li class="nav-item {{ request()->is('guru/jadwal*') ? 'active' : '' }}">
        <a class="nav-link" href="/guru/jadwal">
          <i class="fas fa-fw fa-calendar-alt"></i>
          <span>Jadwal</span></a>
      </li>
      <li class="nav-item {{ request()->is('guru/nilai*') ? 'active' : '' }}">
        <a class="nav-link" href="/guru/nilai">
          <i class="fas fa-fw fa-graduation-cap"></i>
          <span>Nilai</span></a>
      </li>
      <li class="nav-item {{ request()->is('guru/absensi*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->is('guru/absensi*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseAbsensi" aria-expanded="{{ request()->is('guru/absensi*') ? 'true' : 'false' }}" aria-controls="collapseAbsensi">
          <i class="fas fa-fw fa-user-check"></i>
          <span>Absensi Siswa</span>
        </a>
        <div id="collapseAbsensi" class="collapse {{ request()->is('guru/absensi*') ? 'show' : '' }}" aria-labelledby="headingAbsensi" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item {{ request()->is('guru/absensi') || request()->is('guru/absensi/proses*') ? 'active' : '' }}" href="{{ route('guru.absensi.index') }}">Input Absensi</a>
            <a class="collapse-item {{ request()->is('guru/absensi/laporan*') ? 'active' : '' }}" href="{{ route('guru.absensi.laporan') }}">Laporan Absensi</a>
          </div>
        </div>
      </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Lainnya
    </div>
      <li class="nav-item {{ request()->is('guru/info*') ? 'active' : '' }}">
        <a class="nav-link" href="/guru/info">
          <i class="fas fa-fw fa-newspaper"></i>
          <span>Info</span></a>
      </li>
      <li class="nav-item {{ request()->is('guru/profile*') ? 'active' : '' }}">
        <a class="nav-link" href="/guru/profile">
          <i class="fas fa-fw fa-user"></i>
          <span>Profile</span></a>
      </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->