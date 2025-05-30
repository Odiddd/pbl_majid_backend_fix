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
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id('reservasi_id');
            $table->string('nama_pemesan');
            $table->string('kontak_pemesan');
            $table->unsignedBigInteger('tempat_reservasi_id')->index();
            $table->string('nama_acara');
            $table->date('tanggal_acara');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->double('jumlah_tamu')->nullable();
            $table->enum('status_reservasi', ['menunggu', 'dikonfirmasi', 'dijadwalkan', 'dilaksanakan', 'selesai', 'batal'])->default('menunggu');
            $table->string('mengetahui')->nullable();
            $table->double('tagihan')->nullable()->default(0);
            $table->enum('status_pembayaran', ['pending', 'success', 'failed'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->boolean('masuk_transaksi')->default(false);
            $table->timestamps();

            $table->foreign('tempat_reservasi_id')->references('tempat_reservasi_id')->on('tempat_reservasi');
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
