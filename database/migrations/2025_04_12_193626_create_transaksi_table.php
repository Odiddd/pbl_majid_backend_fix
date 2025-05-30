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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('transaksi_id');
            $table->enum('kategori', ['pemasukan', 'pengeluaran']);
            $table->unsignedBigInteger('jenis_transaksi_id')->index();
            $table->double('nominal');
            $table->string('sumber')->nullable()->default('hamba Allah');
            $table->string('mengetahui');
            $table->enum('status', ['draft', 'valid'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('jenis_transaksi_id')->references('jenis_transaksi_id')->on('jenis_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
