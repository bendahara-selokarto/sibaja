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
        Schema::create('daftar_penyedia', function (Blueprint $table) {
        $table->id();
        // Relasi ke users (bigint unsigned)
        $table->unsignedBigInteger('user_id');
        $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->cascadeOnDelete();

        // Relasi ke penyedias (uuid)
        $table->uuid('penyedia_id');
        $table->foreign('penyedia_id')
            ->references('id')
            ->on('penyedias')
            ->cascadeOnDelete();

        $table->timestamps();

        // agar tidak ada duplikat user-penyedia
        $table->unique(['user_id', 'penyedia_id']);
    });
     }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_penyedia');
    }
};
