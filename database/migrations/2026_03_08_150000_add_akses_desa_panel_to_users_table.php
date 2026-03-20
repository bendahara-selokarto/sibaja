<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('akses_desa_panel')->default(false)->after('role');
        });

        DB::table('users')
            ->whereIn(DB::raw('lower(desa)'), [
                'pecalungan',
                'bandung',
                'gombong',
                'randu',
                'siguci',
                'pretek',
                'selokarto',
                'gemuh',
                'gumawang',
                'keniten',
            ])
            ->update(['akses_desa_panel' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('akses_desa_panel');
        });
    }
};
