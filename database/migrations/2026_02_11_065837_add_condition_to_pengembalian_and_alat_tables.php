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
        // Add condition column to pengembalian table to track return condition
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->string('kondisi_alat', 20)->default('baik')->after('denda'); // Condition: baik (good) or rusak (damaged)
        });

        // Add a pivot table to track individual equipment return conditions
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id('id_detail_pengembalian');
            $table->unsignedBigInteger('id_pengembalian');
            $table->unsignedBigInteger('id_alat');
            $table->integer('jumlah_dikembalikan');
            $table->string('kondisi_alat', 20); // baik (good) or rusak (damaged)
            $table->timestamps();

            $table->foreign('id_pengembalian')->references('id_pengembalian')->on('pengembalian')->onDelete('cascade');
            $table->foreign('id_alat')->references('id_alat')->on('alat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn('kondisi_alat');
        });
    }
};
