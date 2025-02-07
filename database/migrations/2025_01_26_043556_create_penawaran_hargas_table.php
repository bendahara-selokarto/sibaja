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
        Schema::create('penawaran_hargas', function (Blueprint $table) {
            $table->id();
            $table->integer('pemberitahuan_id');
            $table->foreignId('kegiatan_id')->constrained()->cascadeOnDelete();
            $table->dateTime('tgl_penawaran_1')->nullable();
            $table->dateTime('tgl_penawaran_2')->nullable();
            $table->dateTime('penyedia_1')->nullable();
            $table->dateTime('penyedia_2')->nullable();
            $table->string('item_penawaran_1')->nullable();
            $table->string('item_penawaran_2')->nullable();
            $table->string('harga_penawaran_1')->nullable();
            $table->string('harga_penawaran_2')->nullable();
            $table->string('no_penawaran_1')->nullable();
            $table->string('no_penawaran_2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penawaran_hargas');
    }
};
