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
        Schema::table('pengembalian', function (Blueprint $table) {
            // Add kondisi_alat column if it doesn't exist
            if (!Schema::hasColumn('pengembalian', 'kondisi_alat')) {
                $table->string('kondisi_alat', 20)->default('baik')->after('denda');
            }
            // Add deskripsi_kerusakan column
            if (!Schema::hasColumn('pengembalian', 'deskripsi_kerusakan')) {
                $table->text('deskripsi_kerusakan')->nullable()->after('kondisi_alat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn('deskripsi_kerusakan');
        });
    }
};
