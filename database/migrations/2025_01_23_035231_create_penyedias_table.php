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
        Schema::create('penyedias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_desa')->nullable()->default('-');
            $table->string('nama_penyedia')->nullable()->default('-');
            $table->string('alamat_penyedia')->nullable()->default('-');
            $table->string('nama_pemilik')->nullable()->default('-');
            $table->string('alamat_pemilik')->nullable()->default('-');
            $table->string('nomor_hp')->nullable()->default('-');
            $table->string('nomor_identitas')->nullable()->default('-');
            $table->string('nomor_npwp')->nullable()->default('-');
            $table->string('nomor_izin_usaha')->nullable()->default('-');
            $table->string('jabata_pemilik')->nullable()->default('pemilik');
            $table->string('instansi_pemberi_izin_usaha')->nullable()->default('-');
            $table->string('logo_penyedia')->nullable();
            $table->string('rekening')->nullable();
            $table->string('bank')->nullable();
            $table->string('atas_nama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyedias');
    }
};
