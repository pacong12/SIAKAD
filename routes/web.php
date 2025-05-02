<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', 'HomeController@index');
Route::get('/home/info/{slug}', 'HomeController@infoLebih');

// Route tambahan untuk destroy siswa

Route::prefix('/')
    ->namespace('Admin')
    ->group(function(){
        // Admin dashboard route
        Route::get('/admin', 'DashboardController@index')
        ->name('dashboard.admin')
        ->middleware('auth', 'check:admin');
        
        // Guru dashboard route
        Route::get('/guru', 'DashboardController@index')
        ->name('dashboard.guru')
        ->middleware('auth', 'check:guru');
        
        // Login routes
        Route::get('/', 'AuthController@login')
        ->name('login');
        Route::post('/postlogin', 'AuthController@postlogin')
        ->name('postlogin');
        Route::get('/logout', 'AuthController@logout')
        ->name('logout');
        Route::get('/resetpass', 'AuthController@reset')
        ->name('reset');
        Route::post('/postreset', 'AuthController@postreset')
        ->name('postreset');
        
        // Admin routes
        Route::group(['middleware' => ['auth', 'check:admin'], 'prefix' => 'admin'], function(){
            Route::get('siswa', 'SiswaController@index');
            Route::get('siswa/create', 'SiswaController@create');
            Route::post('siswa/store', 'SiswaController@store');
            Route::get('siswa/{siswa}/show', 'SiswaController@show');
            Route::get('siswa/{siswa}/edit', 'SiswaController@edit');
            Route::put('siswa/{siswa}/update', 'SiswaController@update')->name('siswa.update');
            Route::get('siswa/{siswa}/destroy', 'SiswaController@destroy');
            Route::get('siswa/{siswa}/nilaiedit', 'SiswaController@nilaiedit');
            Route::get('siswa/exportexcel', 'SiswaController@exportExcel');
            Route::post('siswa/importexcel', 'SiswaController@importExcel')->name('importexcel');
            Route::get('siswa/exportpdf', 'SiswaController@exportPdf');
            Route::get('siswa/{siswa}/nilaiexport', 'SiswaController@exportNilaiPdf');
            Route::get('ubahPassword', 'PasswordController@create')->name('password.create');
            Route::put('ubahPassword', 'PasswordController@update')->name('password.update');
            
            Route::resource('kelas', 'KelasController');
            
            Route::get('guru', 'GuruController@index');
            Route::get('guru/create', 'GuruController@create');
            Route::post('guru/store', 'GuruController@store');
            Route::get('guru/{guru}/show', 'GuruController@show');
            Route::get('guru/{guru}/edit', 'GuruController@edit');
            Route::put('guru/{guru}/update', 'GuruController@update');
            Route::get('guru/{guru}/destroy', 'GuruController@destroy');
            Route::post('guru/{guru}/nilai', 'GuruController@nilai');
            Route::get('guru/exportexcel', 'GuruController@exportExcel');
            Route::get('guru/exportpdf', 'GuruController@exportPdf');
            
            Route::get('jadwalmapel', 'JadwalmapelController@index');
            Route::get('jadwalmapel/create', 'JadwalmapelController@create');
            Route::post('jadwalmapel/store', 'JadwalmapelController@store');
            Route::get('jadwalmapel/{jadwalmapel}/show', 'JadwalmapelController@show');
            Route::get('jadwalmapel/{jadwalmapel}/edit', 'JadwalmapelController@edit');
            Route::put('jadwalmapel/{jadwalmapel}/update', 'JadwalmapelController@update');
            Route::get('jadwalmapel/{jadwalmapel}/destroy', 'JadwalmapelController@destroy');
            Route::post('jadwalmapel/{jadwalmapel}/nilai', 'JadwalmapelController@nilai');
            Route::get('jadwalmapel/exportexcel', 'JadwalmapelController@exportExcel');
            Route::get('jadwalmapel/exportexcel/{id}', 'JadwalmapelController@exportExcelPerKelas');
            Route::get('jadwalmapel/exportpdf', 'JadwalmapelController@exportPdf');
            Route::get('jadwalmapel/exportpdf/{id}', 'JadwalmapelController@exportPdfPerKelas');
    
            Route::get('mapel/{mapel}/hapus', 'MapelController@hapus');
            Route::resource('mapel', 'MapelController');
            Route::get('thnakademik/{thnakademik}/hapus', 'ThnakademikController@hapus');
            Route::resource('thnakademik', 'ThnakademikController');
            Route::resource('sekolah', 'SekolahController');
            Route::get('jenispem/{jenispem}/hapus', 'JenispemController@hapus');
            Route::resource('jenispem', 'JenispemController');
            Route::get('cetakPembayaran', 'PembayaranController@cetakPembayaran')->name('pembayaran.cetak');
            Route::get('cetakPembayaranPertanggal/{tglawal}/{tglakhir}', 'PembayaranController@cetakPembayaranPertanggal')->name('pembayaran.cetaktgl');
            Route::get('cetakPembayaranPdf', 'PembayaranController@cetakPDF')->name('pembayaran.cetakpdf');
            Route::get('cetakPembayaranExcel', 'PembayaranController@cetakEXCEL')->name('pembayaran.cetakexcel');
            Route::get('pembayaran/{pembayaran}/cetak', 'PembayaranController@cetakDetail')->name('pembayaran.cetakdetail');
            Route::get('pembayaran/{pembayaran}/hapus', 'PembayaranController@hapus');
            Route::post('pembayaran/{pembayaran}/status', 'PembayaranController@updateStatus')->name('pembayaran.status');
            Route::resource('pembayaran', 'PembayaranController');
            Route::get('info/{info}/hapus', 'InfoController@hapus');
            Route::resource('info', 'InfoController');
            Route::get('cetakAbsenSiswa', 'AbsensiswaController@cetakAbsen')->name('absensiswa.cetakAbsen');
            Route::get('cetakAbsenPertanggalSiswa/{tglawal}/{tglakhir}', 'AbsensiswaController@cetakAbsenPertanggal')->name('absensiswa.cetaktgl');
            Route::get('cetakAbsenPdf', 'AbsensiswaController@cetakPDF')->name('absensiswa.cetakpdf');
            Route::get('cetakAbsenExcel', 'AbsensiswaController@cetakEXCEL')->name('absensiswa.cetakexcel');
            Route::resource('absensiswa', 'AbsensiswaController');

            Route::get('user/{user}/hapus', 'UserController@hapus');
            Route::resource('user', 'UserController');
            
            // Admin Nilai Routes
            Route::prefix('nilai')->group(function() {
                Route::get('/', 'AdminNilaiController@index')->name('admin.nilai.index');
                Route::get('/kelas/{kelas_id}', 'AdminNilaiController@pilihMapel')->name('admin.nilai.pilih-mapel');
                Route::get('/kelas/{kelas_id}/mapel/{mapel_id}', 'AdminNilaiController@inputNilai')->name('admin.nilai.input');
                Route::post('/simpan', 'AdminNilaiController@simpanNilai')->name('admin.nilai.simpan');
                
                // Cetak Nilai Routes
                Route::get('/cetak', 'AdminNilaiController@cetakIndex')->name('admin.nilai.cetak');
                Route::get('/cetak/kelas/{kelas_id}', 'AdminNilaiController@cetakSiswaKelas')->name('admin.nilai.cetak-siswa');
                Route::post('/cetak/rapor', 'AdminNilaiController@cetakRapor')->name('admin.nilai.cetak-rapor');
                Route::get('/cetak/rapor/kelas/{kelas_id}', 'AdminNilaiController@cetakRaporKelas')->name('admin.nilai.cetak-rapor-kelas');
            });
        });

        // Siswa routes
        Route::group(['middleware' => ['auth', 'check:siswa']], function(){
            Route::get('siswa/profile', 'SiswaController@profile');
            Route::get('siswa/absen', 'SiswaController@absen');
            Route::post('absensiswa', 'SiswaController@absenpros');
            Route::get('siswa/profile/edit/{id}', 'SiswaController@profileedit');
            Route::get('siswa/nilai', 'SiswaController@lihatNilai');
            Route::get('siswa/jadwal', 'SiswaController@jadwal');
            Route::get('siswa/cetaknilai', 'SiswaController@cetakNilai');
            Route::resource('upload', 'OnlinepembController');
        });

        // Guru routes
        Route::group(['middleware' => ['auth', 'check:guru'], 'prefix' => 'guru'], function(){
            Route::get('profile', 'GuruController@profile');
            Route::get('jadwal', 'JadwalmapelController@jadwal');
            Route::get('jadwal/exportpdf/{id}', 'JadwalmapelController@exportPdfGuru');
            Route::get('jadwal/exportexcel/{id}', 'JadwalmapelController@exportExcelGuru');
            
            // Absensi routes
            Route::get('absensi', 'GuruAbsensiController@index')->name('guru.absensi.index');
            Route::get('absensi/proses/{id}', 'GuruAbsensiController@proses')->name('guru.absensi.proses');
            Route::post('absensi/store', 'GuruAbsensiController@store')->name('guru.absensi.store');
            Route::get('absensi/laporan', 'GuruAbsensiController@laporan')->name('guru.absensi.laporan');
            Route::get('absensi/cetak-pdf', 'GuruAbsensiController@cetakPdf')->name('guru.absensi.cetakPdf');
            Route::get('absensi/export-excel', 'GuruAbsensiController@exportExcel')->name('guru.absensi.exportExcel');
            
            Route::get('nilai', 'NilaiController@index');
            Route::get('masukNilai', 'NilaiController@index')->name('masukNilai');
            Route::get('nilaiProses/{id}', 'NilaiController@proses')->name('nilaiProses');
            Route::post('nilai-simpan-batch', 'NilaiController@nilaiSimpanBatch')->name('nilai.simpan.batch');
            Route::get('get-nilai-siswa', 'NilaiController@getNilaiSiswa')->name('nilai.get-siswa');
            Route::get('info', 'GuruInfoController@index');
            Route::get('info/{slug}', 'GuruInfoController@show');
            
            Route::prefix('siswa')->group(function() {
                Route::get('{siswa}/nilai', 'NilaiController@detail');
                Route::get('{siswa}/nilai/detail', 'NilaiController@detailNilai');
                Route::post('{siswa}/nilaitambah', 'NilaiController@nilai');
                Route::get('{id}/{idmapel}/nilaitambah', 'NilaiController@nilaitambah');
                Route::get('{id}/{idmapel}/hapus', 'NilaiController@nilaihapus');
                Route::post('{id}/nilaiupdate', 'NilaiController@nilaiupdate');
            });
            
            Route::get('cetakNilai/{id}', 'NilaiController@cetakNilai')->name('nilai.cetak');
            Route::get('cetakNilaiPeraka/{id}/{thnakademik}', 'NilaiController@cetakNilaiPeraka')->name('cetaknilai.cetakaka');
        });

        // Pembayaran bulk route
        Route::post('pembayaran/bulk', 'PembayaranController@createBulkPayment')->name('pembayaran.bulk');
    });
