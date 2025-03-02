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
        Schema::create('negosiasi_harga', function (Blueprint $table) {
            $table->uuid('id')->primary();  
            $table->foreignUuid('kegiatan_id')->constrained()->cascadeOnDelete();
            $table->string('rekening_apbdes')->nullable();
            $table->string('kode_desa')->nullable();
            $table->dateTime('tgl_negosiasi')->nullable();
            $table->string('harga_negosiasi')->nullable();
            $table->dateTime('tgl_persetujuan')->nullable();
            $table->dateTime('tgl_perjanjian')->nullable();
            $table->dateTime('tgl_akhir_perjanjian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negosiasi_harga');
    }
};
