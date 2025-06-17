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
        Schema::table('penyedias', function (Blueprint $table) {
            $table->string('kop_surat')->nullable()->after('logo_penyedia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyedias', function (Blueprint $table) {
            $table->dropColumn('kop_surat');
        });
    }
};
