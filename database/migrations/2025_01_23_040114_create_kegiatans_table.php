<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('rekening_apbdes')->nullable()->default('0.0.0.0');
            $table->string('kegiatan')->nullable()->default('Pembangunan');
            $table->string('ketua_tpk')->nullable()->default('ketua TPK');
            $table->string('pka')->nullable()->default('PKA');
            $table->string('kode_desa')->nullable()->default('332514');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
