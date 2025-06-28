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
        Schema::create('penawaran_2', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('penyedia_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('kegiatan_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('pemberitahuan_id')->constrained()->cascadeOnDelete();
            $table->dateTime('tgl_penawaran');
            $table->integer('no_penawaran');
            $table->string('nilai_penawaran');
            $table->string('item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penawaran_2');
    }
};
