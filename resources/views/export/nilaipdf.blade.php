<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{auth()->user()->name}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        h4 {
            text-align: center;
        }
        h3 {
            text-align: center;
        }
    </style>
</head>
<body>
    {{-- {{dd($sekolah)}} --}}
    <h4 class="text-center">Hasil Nilai Siswa</h4>
    <h4>{{ strtoupper($sekolah->nama ?? 'SEKOLAH') }}</h4>
    <h4>TAHUN PELAJARAN {{ $tahunAkademik ? $tahunAkademik->tahun_akademik : date('Y').'/'.((int)date('Y')+1) }} - SEMESTER {{ $tahunAkademik ? $tahunAkademik->semester : '' }}</h4>
    <hr>
    <h6 class="text-left">NISN    : {{auth()->user()->username}}</h6>
    <h6 class="text-left">NAMA     : {{auth()->user()->name}}</h6>
    <table class="table table-bordered text-center mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Mapel</th>
                <th>Nilai UTS</th>
                <th>Nilai UAS</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- {{dd($siswa)}} --}}
            @forelse (auth()->user()->siswa->mapel as $mapel)
                <tr>
                    <td>{{$mapel->nama_mapel}}</td>
                    <td>{{$mapel->pivot->uts}}</td>
                    <td>{{$mapel->pivot->uas}}</td>
                    <td>{{$mapel->pivot->status}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        Data Kosong
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
