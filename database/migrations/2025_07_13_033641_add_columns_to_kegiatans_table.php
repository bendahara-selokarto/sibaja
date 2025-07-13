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
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->string('lokasi_kegiatan')->after('kegiatan')->nullable();
            $table->string('sekretaris_tpk')->after('ketua_tpk')->nullable();
            $table->string('anggota_tpk')->after('ketua_tpk')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn('lokasi_kegiatan');
            $table->dropColumn('sekretaris_tpk');
            $table->dropColumn('anggota_tpk');
        });
    }
};
