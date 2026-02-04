<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KegiatanFactory extends Factory
{
    protected $model = Kegiatan::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'rekening_apbdes' => $this->faker->numerify('5.1.##.##'),
            'kegiatan'        => $this->faker->sentence(3),
            'ketua_tpk'       => $this->faker->name(),
            'pka'             => $this->faker->name(),
            'kode_desa'       => 'D01',
        ];
    }
}
