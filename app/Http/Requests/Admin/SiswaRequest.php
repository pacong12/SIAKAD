<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Mendapatkan ID siswa dari route jika ada (untuk kasus edit)
        $siswaId = $this->route('siswa');
        
        $rules = [
            'nama' => 'required|min:3|string',
            'tpt_lahir' => 'required|min:3',
            'tgl_lahir' => 'required',
            'jns_kelamin' => 'required',
            'agama' => 'required',
            'alamat' => 'required|min:5',
            'nama_ortu' => 'required',
            'asal_sklh' => 'required',
        ];
        
        // Validasi image hanya wajib saat create, tidak wajib saat edit
        if ($this->isMethod('POST')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg';
        }
        
        // Validasi NISN harus unik, kecuali untuk siswa yang sedang diedit
        if ($siswaId) {
            $rules['nisn'] = [
                'required',
                Rule::unique('siswas', 'nisn')->ignore($siswaId)
            ];
        } else {
            $rules['nisn'] = 'required|unique:siswas,nisn';
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'nisn.unique' => 'NISN sudah digunakan',
            'nisn.required' => 'NISN tidak boleh kosong',
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.min' => 'Nama minimal 3 karakter',
            'nama.string' => 'Nama harus huruf',
            'tpt_lahir.required' => 'Tempat tanggal lahir tidak boleh kosong',
            'tpt_lahir.min' => 'Tempat tanggal lahir minimal 3 karakter',
            'tgl_lahir.required' => 'Tanggal lahir tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'alamat.min' => 'Alamat minimal 3 karakter',
            'nama_ortu.required' => 'Nama Orang Tua tidak boleh kosong',
            'asal_sklh.required' => 'Asal Sekolah tidak boleh kosong',
            'image.image' => 'File harus gambar',
            'image.required' => 'Foto harus dimasukan',
            'image.mimes' => 'File harus berformat jpeg,jpg,gif,svg,png'
        ];
    }
}
