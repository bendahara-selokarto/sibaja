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
        Schema::dropIfExists('pemberitahuan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('pemberitahuan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->string('kode_desa')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('rekening_apbdes')->nullable();
            $table->json('penyedia')->nullable();
            $table->dateTime('tgl_surat_pemberitahuan')->nullable();
            $table->dateTime('tgl_batas_akhir_penawaran')->nullable();
            $table->integer('no_pbj')->uniqid();
            $table->string('penyedia1')->nullable();
            $table->string('penyedia2')->nullable();
            $table->timestamps();
        });
    }
};
