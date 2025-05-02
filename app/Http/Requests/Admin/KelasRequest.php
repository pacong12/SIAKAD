<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KelasRequest extends FormRequest
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
            'nama_kelas' => 'nullable|string|max:255',
            'tingkat' => 'required|integer|min:1|max:6',
            'guru_id' => 'nullable|exists:gurus,id',
            'thnakademik_id' => 'required|exists:thnakademiks,id',
            'deskripsi' => 'nullable|string'
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'tingkat.required' => 'Tingkat kelas harus diisi',
            'tingkat.min' => 'Tingkat kelas minimal 1',
            'tingkat.max' => 'Tingkat kelas maksimal 6',
            'thnakademik_id.required' => 'Tahun akademik harus dipilih',
            'thnakademik_id.exists' => 'Tahun akademik tidak valid'
        ];
    }
}
