<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class PemberitahuanRequest extends FormRequest
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
            'rekening_apbdes' => 'required|string|max:255',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'penyedia' => 'required|array|size:2',
            'penyedia.*' => [
                'required',
                'distinct',
                Rule::exists('daftar_penyedia', 'penyedia_id')->where(
                    fn ($query) => $query->where('user_id', Auth::id())
                ),
            ],
            'no_pbj' => 'required|integer|min:1',
            'tgl_pemberitahuan' => 'required|date',
            'uraian' => 'required|array|min:1',
            'uraian.*' => 'required|string|max:255',
            'volume' => 'required|array|min:1',
            'volume.*' => 'nullable|numeric|min:0',
            'satuan' => 'required|array|min:1',
            'satuan.*' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Field :attribute wajib diisi.',
            'array' => 'Field :attribute harus berupa daftar.',
            'size' => 'Field :attribute harus berisi tepat :size item.',
            'distinct' => 'Field :attribute tidak boleh duplikat.',
            'exists' => 'Field :attribute tidak ditemukan.',
            'integer' => 'Field :attribute harus berupa angka bulat.',
            'numeric' => 'Field :attribute harus berupa angka.',
            'date' => 'Field :attribute harus berupa tanggal yang valid.',
            'min' => 'Field :attribute minimal :min.',
            'max' => 'Field :attribute maksimal :max karakter.',
            'string' => 'Field :attribute harus berupa teks.',
        ];
    }

    public function attributes(): array
    {
        return [
            'rekening_apbdes' => 'Rekening APBDes',
            'kegiatan_id' => 'Kegiatan',
            'penyedia' => 'Penyedia',
            'penyedia.*' => 'Penyedia',
            'no_pbj' => 'Nomor Urut PBJ',
            'tgl_pemberitahuan' => 'Tanggal Surat',
            'uraian' => 'Uraian Belanja',
            'uraian.*' => 'Uraian Belanja',
            'volume' => 'Volume',
            'volume.*' => 'Volume',
            'satuan' => 'Satuan',
            'satuan.*' => 'Satuan',
        ];
    }

    public function belanjaItems(): Collection
    {
        $validated = $this->validated();
        $volume = $validated['volume'] ?? [];
        $satuan = $validated['satuan'] ?? [];

        return collect($validated['uraian'])
            ->values()
            ->map(function (string $uraian, int $key) use ($volume, $satuan) {
                return [
                    'uraian' => $uraian,
                    'volume' => $volume[$key] ?? null,
                    'satuan' => $satuan[$key] ?? null,
                ];
            });
    }

    public function pemberitahuanPayload(): array
    {
        $validated = $this->validated();
        $belanja = $this->belanjaItems();

        return [
            'rekening_apbdes' => $validated['rekening_apbdes'],
            'kegiatan_id' => $validated['kegiatan_id'],
            'penyedia' => array_values($validated['penyedia']),
            'no_pbj' => $validated['no_pbj'],
            'pekerjaan' => $belanja->implode('uraian', ', '),
            'tgl_surat_pemberitahuan' => $validated['tgl_pemberitahuan'],
            'tgl_batas_akhir_penawaran' => Carbon::parse($validated['tgl_pemberitahuan'])->addDays(3),
        ];
    }
}
