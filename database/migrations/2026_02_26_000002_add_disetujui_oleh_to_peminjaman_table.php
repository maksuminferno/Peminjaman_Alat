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
        Schema::table('peminjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman', 'disetujui_oleh')) {
                $table->unsignedBigInteger('disetujui_oleh')->nullable()->after('id_user');
                $table->foreign('disetujui_oleh')->references('id_user')->on('users')->onDelete('SET NULL');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['disetujui_oleh']);
            $table->dropColumn('disetujui_oleh');
        });
    }
};
