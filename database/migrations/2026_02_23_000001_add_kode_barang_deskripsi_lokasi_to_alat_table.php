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
        Schema::table('alat', function (Blueprint $table) {
            $table->string('kode_barang')->nullable()->after('nama_alat');
            $table->text('deskripsi')->nullable()->after('id_kategori');
            $table->string('lokasi')->nullable()->after('stok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn(['kode_barang', 'deskripsi', 'lokasi']);
        });
    }
};
