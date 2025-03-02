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
        Schema::table('users', function (Blueprint $table) {
            $table->string('desa')->nullable()->default('desa')->after('name');
            $table->string('kecamatan')->nullable()->default('pecalungan')->after('desa');
            $table->string('kepala_desa')->nullable()->default('kepala_desa')->after('kecamatan');
            $table->string('sekretaris_desa')->nullable()->default('sekretaris_desa')->after('kepala_desa');
            $table->string('bendahara_desa')->nullable()->default('bendahara_desa')->after('sekretaris_desa');
            $table->string('alamat_kantor')->nullable()->default('Jl. Raya')->after('bendahara_desa');
            $table->string('website')->nullable()->default('desa.id')->after('alamat_kantor');
            $table->string('kode_desa')->nullable()->default('332514....')->after('website');
            $table->string('tahun_anggaran')->nullable()->default('2024')->after('kode_desa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('desa');
            $table->dropColumn('kecamatan');
            $table->dropColumn('kepala_desa');
            $table->dropColumn('sekretaris_desa');
            $table->dropColumn('bendahara_desa');
            $table->dropColumn('alamat_kantor');
            $table->dropColumn('website');
            $table->dropColumn('kode_desa');
            $table->dropColumn('tahun_anggaran');
        });
    }
};
