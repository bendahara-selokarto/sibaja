<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenyediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
           
        return [
            'nama_penyedia' => 'required|string|max:255',
            'alamat_penyedia' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'alamat_pemilik' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
            'nomor_identitas' => 'required|string|max:50',
            'nomor_npwp' => 'nullable|string|max:50',
            'no_siup' => 'nullable|string|max:100',
            'jabatan_pemilik' => 'required|string|max:100',
            'penerbit_siup' => 'nullable|string|max:255',
            'rekening' => 'nullable|string|max:50',
            'bank' => 'nullable|string|max:100',
            'atas_nama' => 'nullable|string|max:255',
            'logo_penyedia' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'kop_surat' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,svg|max:4096',
            'data_dukung' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:5120',
            'kabupaten' => 'required|string|max:100',
            
        ];
    }
}
