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
            if (!Schema::hasColumn('pengembalian', 'bukti_foto')) {
                $table->string('bukti_foto')->nullable()->after('deskripsi_kerusakan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            if (Schema::hasColumn('pengembalian', 'bukti_foto')) {
                $table->dropColumn('bukti_foto');
            }
        });
    }
};
