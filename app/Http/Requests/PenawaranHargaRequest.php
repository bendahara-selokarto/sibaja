<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class PenawaranHargaRequest extends FormRequest
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
            'pemberitahuan_id' => 'required|exists:pemberitahuans,id',
            'penyedia' => 'required|exists:penyedias,id',
            'tgl_surat_penawaran' => 'required|date',
            'no_penawaran' => 'required|string|max:255',
            'pemenang' => 'required|boolean',
            'harga_satuan' => 'required|array|min:1',
            'harga_satuan.*' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Field :attribute wajib diisi.',
            'exists' => 'Field :attribute tidak ditemukan.',
            'date' => 'Field :attribute harus berupa tanggal yang valid.',
            'string' => 'Field :attribute harus berupa teks.',
            'boolean' => 'Field :attribute harus bernilai benar atau salah.',
            'array' => 'Field :attribute harus berupa daftar.',
            'numeric' => 'Field :attribute harus berupa angka.',
            'min' => 'Field :attribute minimal :min.',
            'max' => 'Field :attribute maksimal :max karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'pemberitahuan_id' => 'Pemberitahuan',
            'penyedia' => 'Penyedia',
            'tgl_surat_penawaran' => 'Tanggal Surat Penawaran',
            'no_penawaran' => 'Nomor Penawaran',
            'pemenang' => 'Status Pemenang',
            'harga_satuan' => 'Harga Satuan',
            'harga_satuan.*' => 'Harga Satuan',
        ];
    }

    public function isWinner(): bool
    {
        return $this->boolean('pemenang');
    }

    public function hargaPenawaranPayload(): array
    {
        return collect($this->validated('harga_satuan', []))
            ->map(fn ($harga) => ['harga_satuan' => $harga])
            ->all();
    }
}
