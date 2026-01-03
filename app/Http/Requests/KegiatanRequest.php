<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KegiatanRequest extends FormRequest
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
            'rekening_apbdes' => 'required|string',
            'sumber_dana' => 'required|string',
            'kegiatan' => 'required|string',
            'lokasi_kegiatan' => 'required|string',
            'ketua_tpk' => 'required|string',
            'sekretaris_tpk' => 'required|string',
            'anggota_tpk' => 'required|string',
            'nomor_sk_tpk' => 'required',
            'tgl_sk_tpk' => 'required|date',
            'nomor_sk_pka' => 'required',
            'tgl_sk_pka' => 'required|date',
            'pph_22' => 'required|numeric',
            'pka' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'numeric' => ':attribute harus berupa angka.',
        ];
    }
    
    public function attributes(): array
    {
        return [
            'rekening_apbdes' => 'Rekening APBDes',
            'sumber_dana' => 'Sumber Dana',
            'kegiatan' => 'Kegiatan',
            'lokasi_kegiatan' => 'Lokasi Kegiatan',
            'ketua_tpk' => 'Ketua TPK',
            'sekretaris_tpk' => 'Sekretaris TPK',
            'anggota_tpk' => 'Anggota TPK',
            'nomor_sk_tpk' => 'Nomor SK TPK',
            'tgl_sk_tpk' => 'Tanggal SK TPK',
            'nomor_sk_pka' => 'Nomor SK PKA',
            'tgl_sk_pka' => 'Tanggal SK PKA',
            'pph_22' => 'PPH 22',
            'pka' => 'PKA',
        ];
    }
}
