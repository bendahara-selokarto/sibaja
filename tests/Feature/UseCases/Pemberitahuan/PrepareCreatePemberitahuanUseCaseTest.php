<?php

namespace Tests\Feature\UseCases\Pemberitahuan;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\UseCases\Pemberitahuan\PrepareCreatePemberitahuanUseCase;
use App\UseCases\Pemberitahuan\PrepareCreatePemberitahuanInput;


class PrepareCreatePemberitahuanUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_menyiapkan_data_create_pemberitahuan()
{
    $kegiatan = Kegiatan::factory()->create();

    Pemberitahuan::factory()->create([
        'kegiatan_id' => $kegiatan->id,
        'kode_desa'   => 'D01',
        'no_pbj'      => 1,
    ]);

    $input = new PrepareCreatePemberitahuanInput(
    kegiatanId: $kegiatan->id,
    kodeDesa: 'D01'
);
$useCase = new PrepareCreatePemberitahuanUseCase();

$result = $useCase->execute($input);

$this->assertEquals(2, $result->noPbJ);
$this->assertEquals($kegiatan->id, $result->kegiatan->id);



   
}

}
