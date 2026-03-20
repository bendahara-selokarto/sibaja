<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class NegosiasiRequest extends FormRequest
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
            'tgl_persetujuan' => 'required|date',
            'tgl_negosiasi' => 'required|date',
            'tgl_akhir_perjanjian' => 'required|date',
            'harga_satuan_negosiasi' => 'required|array|min:1',
            'harga_satuan_negosiasi.*' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Field :attribute wajib diisi.',
            'exists' => 'Field :attribute tidak ditemukan.',
            'date' => 'Field :attribute harus berupa tanggal yang valid.',
            'array' => 'Field :attribute harus berupa daftar.',
            'numeric' => 'Field :attribute harus berupa angka.',
            'min' => 'Field :attribute minimal :min.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kegiatan_id' => 'Kegiatan',
            'tgl_persetujuan' => 'Tanggal Persetujuan',
            'tgl_negosiasi' => 'Tanggal Negosiasi',
            'tgl_akhir_perjanjian' => 'Tanggal Akhir Perjanjian',
            'harga_satuan_negosiasi' => 'Harga Satuan Negosiasi',
            'harga_satuan_negosiasi.*' => 'Harga Satuan Negosiasi',
        ];
    }

    public function negosiasiPayload(): array
    {
        $validated = $this->validated();

        return [
            'kegiatan_id' => $validated['kegiatan_id'],
            'tgl_persetujuan' => Carbon::parse($validated['tgl_persetujuan']),
            'tgl_negosiasi' => Carbon::parse($validated['tgl_negosiasi']),
            'tgl_akhir_perjanjian' => Carbon::parse($validated['tgl_akhir_perjanjian']),
        ];
    }

    public function hargaNegosiasiPayload(): array
    {
        return collect($this->validated('harga_satuan_negosiasi', []))
            ->map(fn ($harga) => ['harga_satuan' => $harga])
            ->all();
    }
}
