<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuruRequest;
use App\Guru;
use App\User;
use App\Mapel;
use App\Jadwalmapel;
use App\Exports\GuruExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Guru::all();

        return view('pages.admin.guru.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuruRequest $request)
    {
        // insert ke table users
        $user = new User;
        $user->role = 'guru';
        $user->name = $request->nama;
        $user->image = $request->file('image')->store(
            'assets/gallery', 'public'
        );
        $user->username = $request->nip;
        $user->password = bcrypt($request->nip);
        $user->remember_token = Str::random(60);
        $user->save();

        // insert table
        $request->request->add(['user_id' => $user->id]);
        $data = $request->all();
        $data['image'] = $request->file('image')->store(
            'assets/gallery', 'public'
        );
        
        Guru::create($data);

        return redirect('/admin/guru')->with('status', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Guru::findOrFail($id);

        return view('pages.admin.guru.detail', [
            'item' => $item
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Guru::findOrFail($id);

        return view('pages.admin.guru.edit', [
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi manual karena tidak bisa menggunakan GuruRequest yang mewajibkan image
        $this->validate($request, [
            'nip' => 'required|unique:gurus,nip,'.$id,
            'nama' => 'required|string|min:3',
            'tpt_lahir' => 'required|min:3',
            'tgl_lahir' => 'required',
            'jns_kelamin' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
        ], [
            'nip.unique' => 'NIP sudah digunakan',
            'nip.required' => 'NIP tidak boleh kosong',
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.min' => 'Nama minimal 3 karakter',
            'nama.string' => 'Nama harus huruf',
            'tpt_lahir.required' => 'Tempat tanggal lahir tidak boleh kosong',
            'tpt_lahir.min' => 'Tempat tanggal lahir minimal 3 karakter',
            'tgl_lahir.required' => 'Tanggal lahir tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'alamat.min' => 'Alamat minimal 3 karakter',
            'image.image' => 'File harus gambar',
            'image.mimes' => 'File harus berformat jpeg,jpg,gif,svg,png'
        ]);
        
        $data = Guru::findOrFail($id);
        $update_guru = $data->user_id;

        if($request->hasFile('image')) {
            // Hapus file lama jika ada
            if($data->image && Storage::disk('public')->exists($data->image)) {
                Storage::disk('public')->delete($data->image);
            }
            $image = $request->file('image')->store('assets/gallery', 'public');
        } elseif($data->image) {
            $image = $data->image;
        } else {
            $image = null;
        }

        $data->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'tpt_lahir' => $request->tpt_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'jns_kelamin' => $request->jns_kelamin,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'image' => $image
        ]);

        $baru = User::find($update_guru);
        if($baru) {
            $baru->name = $request->nama;
            $baru->image = $image;
            $baru->username = $request->nip;
            $baru->save();
        }

        return redirect('/admin/guru')->with('status', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Guru::findOrFail($id);
        
        // Hapus file gambar jika ada
        if($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
        
        $item->delete();

        Jadwalmapel::where('guru_id', $id)->delete();

        $hapus_guru = $item->user_id;
        User::where('id', $hapus_guru)->delete();

        return redirect('/admin/guru')->with('status', 'Data Berhasil Dihapus');
    }

    public function profile()
    {
        return view('pages.admin.guru.profile');
    }

    public function exportExcel() 
    {
        return Excel::download(new GuruExport, 'Guru.xlsx');
    }

    public function exportPdf()
    {
        // Meningkatkan batas waktu eksekusi
        ini_set('max_execution_time', 300);
        
        // Get data guru yang diurutkan berdasarkan nama
        $guru = Guru::orderBy('nama')->get();
        
        $pdf = PDF::loadView('export.gurupdf', ['guru' => $guru]);
        
        // Menggunakan setting yang lebih efisien
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['dpi' => 100, 'defaultFont' => 'sans-serif']);
        
        return $pdf->download('guru.pdf');
    }   

    public function jadwal()
    {
        $items = Jadwalmapel::all();
        return view('pages.admin.guru.jadwal', compact('items'));
    }
}
