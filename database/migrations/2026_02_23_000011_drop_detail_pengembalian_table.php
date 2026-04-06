<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn('kondisi_alat');
        });

        Schema::dropIfExists('detail_pengembalian');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->string('kondisi_alat', 20)->default('baik')->after('denda');
        });

        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id('id_detail_pengembalian');
            $table->unsignedBigInteger('id_pengembalian');
            $table->unsignedBigInteger('id_alat');
            $table->integer('jumlah_dikembalikan');
            $table->string('kondisi_alat', 20);
            $table->timestamps();

            $table->foreign('id_pengembalian')->references('id_pengembalian')->on('pengembalian')->onDelete('cascade');
            $table->foreign('id_alat')->references('id_alat')->on('alat')->onDelete('cascade');
        });
    }
};
