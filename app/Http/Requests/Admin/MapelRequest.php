<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MapelRequest extends FormRequest
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
        return [
            'kode_mapel' => 'required|unique:mapels,kode_mapel',
            'nama_mapel' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'kode_mapel.required' => 'Kode Mapel tidak boleh kosong',
            'kode_mapel.unique' => 'Kode Mapel telah digunakan',
            'nama_mapel.required' => 'Nama Mapel tidak boleh kosong'
        ];
    }
}
