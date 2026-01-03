<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
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
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'tgl_pembayaran_cms' => 'required|date',
            'tgl_invoice' => 'required|date',
        ];
    }
    public function messages()
    {
        return [
            'kegiatan_id.required' => 'ID Kegiatan wajib diisi.',
            'kegiatan_id.exists' => 'ID Kegiatan tidak ditemukan.',
            'tgl_pembayaran_cms.required' => 'Tanggal Pembayaran CMS wajib diisi.',
            'tgl_pembayaran_cms.date' => 'Tanggal Pembayaran CMS harus berupa tanggal yang valid.',
            'tgl_invoice.required' => 'Tanggal Invoice wajib diisi.',
            'tgl_invoice.date' => 'Tanggal Invoice harus berupa tanggal yang valid.',
        ];
    }
    public function attributes()
    {
        return [
            'kegiatan_id' => 'ID Kegiatan',
            'tgl_pembayaran_cms' => 'Tanggal Pembayaran CMS',
            'tgl_invoice' => 'Tanggal Invoice',
        ];
    }
}
