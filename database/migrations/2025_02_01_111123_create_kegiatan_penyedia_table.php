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
        Schema::create('kegiatan_penyedia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyedia_id')->constrained('penyedias')->onDelete('cascade'); 
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_penyedia');
    }
};
