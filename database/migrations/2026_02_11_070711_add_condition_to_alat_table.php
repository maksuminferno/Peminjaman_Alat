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
        Schema::table('alat', function (Blueprint $table) {
            // Add condition column to track equipment condition (baik/rusak)
            $table->string('kondisi', 20)->default('baik')->after('stok'); // baik (good) or rusak (damaged)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn('kondisi');
        });
    }
};
