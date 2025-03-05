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
            $table->string('desa')->nullable()->after('name');
            $table->string('kecamatan')->nullable()->after('desa');
            $table->string('kepala_desa')->nullable()->after('kecamatan');
            $table->string('sekretaris_desa')->nullable()->after('kepala_desa');
            $table->string('bendahara_desa')->nullable()->after('sekretaris_desa');
            $table->string('alamat_kantor')->nullable()->after('bendahara_desa');
            $table->string('website')->nullable()->after('alamat_kantor')->unique();
            $table->string('kode_desa')->nullable()->after('website')->unique();
            $table->year('tahun_anggaran')->nullable()->after('kode_desa');
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
