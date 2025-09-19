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
            $table->unsignedInteger('nomor_sk_tpk')->nullable();
            $table->dateTime('tgl_sk_tpk')->nullable();
            $table->unsignedInteger('nomor_sk_pka')->nullable();
            $table->dateTime('tgl_sk_pka')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn('nomor_sk_tpk');
            $table->dropColumn('tgl_sk_tpk');
            $table->dropColumn('nomor_sk_pka');
            $table->dropColumn('tgl_sk_pka');
        });
    }
};
