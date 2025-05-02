<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuruRequest extends FormRequest
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
        $rules = [
            'nama' => 'required|string|min:3',
            'tpt_lahir' => 'required|min:3',
            'tgl_lahir' => 'required',
            'jns_kelamin' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
        ];

        // Jika request untuk membuat data baru (POST)
        if ($this->isMethod('post')) {
            $rules['nip'] = 'required|unique:gurus,nip';
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg';
        } 
        // Jika request untuk update data (PUT/PATCH)
        else if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['nip'] = 'required|unique:gurus,nip,' . $this->route('guru');
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg';
        }

        return $rules;
    }

    public function messages()
    {
        return [
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
            'image.required' => 'Foto harus dimasukan',
            'image.mimes' => 'File harus berformat jpeg,jpg,gif,svg,png'
        ];
    }
}
