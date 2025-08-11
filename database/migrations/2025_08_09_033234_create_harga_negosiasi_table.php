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
        Schema::create('harga_negosiasi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('negosiasi_id')->constrained('negosiasi_harga')->cascadeOnDelete();
            $table->decimal('harga_satuan', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_negosiasi');
    }
};
