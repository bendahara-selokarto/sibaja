<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $requiredIfCreating = Rule::requiredIf(!$this->route('penyedia'));

        return [
            'nama_penyedia' => 'required|string|max:255',
            'alamat_penyedia' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'alamat_pemilik' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
            'nomor_identitas' => 'required|string|max:50',
            'nomor_npwp' => [$requiredIfCreating, 'nullable', 'string', 'max:50'],
            'no_siup' => [$requiredIfCreating, 'nullable', 'string', 'max:100'],
            'jabatan_pemilik' => 'required|string|max:100',
            'penerbit_siup' => [$requiredIfCreating, 'nullable', 'string', 'max:255'],
            'rekening' => [$requiredIfCreating, 'nullable', 'string', 'max:50'],
            'bank' => [$requiredIfCreating, 'nullable', 'string', 'max:100'],
            'atas_nama' => [$requiredIfCreating, 'nullable', 'string', 'max:255'],
            'kop_surat' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,svg|max:4096',
            'clear_kop_surat' => 'nullable|boolean',
            'kabupaten' => 'required|string|max:100',
            
        ];
    }
    public function messages(): array
    {
        return [
            'required' => 'Field :attribute wajib diisi.',
            'string' => 'Field :attribute harus berupa teks.',
            'max' => 'Field :attribute maksimal :max karakter.',
            'image' => 'Field :attribute harus berupa gambar.',
            'mimes' => 'Field :attribute harus berupa file dengan format: :values.',
            'file' => 'Field :attribute harus berupa file.',
        ];
    }
    public function attributes(): array
    {
        return [
            'nama_penyedia' => 'Nama Penyedia',
            'alamat_penyedia' => 'Alamat Penyedia',
            'nama_pemilik' => 'Nama Pemilik',
            'alamat_pemilik' => 'Alamat Pemilik',
            'nomor_hp' => 'Nomor HP',
            'nomor_identitas' => 'Nomor Identitas',
            'nomor_npwp' => 'Nomor NPWP',
            'no_siup' => 'Nomor SIUP',
            'jabatan_pemilik' => 'Jabatan Pemilik',
            'penerbit_siup' => 'Penerbit SIUP',
            'rekening' => 'Rekening',
            'bank' => 'Bank',
            'atas_nama' => 'Atas Nama',
            'kop_surat' => 'Kop Surat',
            'clear_kop_surat' => 'Hapus Kop Surat',
            'kabupaten' => 'Kabupaten',
        ];
    }
    
}
