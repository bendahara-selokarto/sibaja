<?php

namespace App\Actions\Penyedia;

use App\Contracts\PenyediaRepositoryInterface;
use App\Http\Requests\PenyediaRequest;
use App\Models\Penyedia;
use App\Services\PenyediaMediaService;

class CreatePenyediaAction
{
    public function __construct(
        private readonly PenyediaRepositoryInterface $penyediaRepository,
        private readonly PenyediaMediaService $penyediaMediaService
    ) {
    }

    public function execute(PenyediaRequest $request): Penyedia
    {
        $media = $this->penyediaMediaService->buildMediaPayload($request);

        return $this->penyediaRepository->store([
            'created_by' => $request->user()->id,
            'nama_penyedia' => $request->nama_penyedia,
            'alamat_penyedia' => $request->alamat_penyedia,
            'nama_pemilik' => $request->nama_pemilik,
            'alamat_pemilik' => $request->alamat_pemilik,
            'nomor_hp' => $request->nomor_hp,
            'nomor_identitas' => $request->nomor_identitas,
            'nomor_npwp' => $request->nomor_npwp,
            'nomor_izin_usaha' => $request->no_siup,
            'jabata_pemilik' => $request->jabatan_pemilik,
            'instansi_pemberi_izin_usaha' => $request->penerbit_siup,
            'rekening' => $request->rekening,
            'bank' => $request->bank,
            'atas_nama' => $request->atas_nama,
            'kabupaten' => $request->kabupaten,
            ...$media,
        ]);
    }
}
