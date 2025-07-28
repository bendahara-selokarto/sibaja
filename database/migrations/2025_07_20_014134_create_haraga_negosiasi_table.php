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
        Schema::create('haraga_negosiasi', function (Blueprint $table) {
             $table->uuid('id')->primary();
            $table->foreignUuid('negosiasi_id')->constrained('negosiasi  ')->cascadeOnDelete();
            $table->integer('harga_satuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('haraga_negosiasi');
    }
};
