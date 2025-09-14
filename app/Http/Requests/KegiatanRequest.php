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
            'rekening_apbdes.required' => 'Rekening APBDes wajib diisi.',
            'kegiatan.required'        => 'Nama kegiatan wajib diisi.',
            'lokasi_kegiatan.required' => 'Lokasi kegiatan wajib diisi.',
            'ketua_tpk.required'       => 'Nama Ketua TPK wajib diisi.',
            'sekretaris_tpk.required'  => 'Nama Sekretaris TPK wajib diisi.',
            'anggota_tpk.required'     => 'Nama Anggota TPK wajib diisi.',
            'nomor_sk_tpk.required'    => 'Nomor SK TPK wajib diisi.',
            'tgl_sk_tpk.required'      => 'Tanggal SK TPK wajib diisi.',
            'tgl_sk_tpk.date'          => 'Tanggal SK TPK harus berupa tanggal yang valid.',
            'nomor_sk_pka.required'    => 'Nomor SK PKA wajib diisi.',
            'tgl_sk_pka.required'      => 'Tanggal SK PKA wajib diisi.',
            'tgl_sk_pka.date'          => 'Tanggal SK PKA harus berupa tanggal yang valid.',
            'pka.required'             => 'Nama PKA wajib diisi.',
        ];
    }
}
