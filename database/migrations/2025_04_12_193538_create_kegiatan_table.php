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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id('kegiatan_id');
            $table->string('nama_kegiatan');
            $table->text('isi'); 
            $table->date('tanggal'); 
            $table->time('waktu_mulai')->nullable(); 
            $table->time('waktu_selesai')->nullable(); 
            $table->string('lokasi'); 
            $table->enum('status',['dijadwalkan','dilaksanakan','selesai','dibatalkan'])->default('dijadwalkan'); 
            $table->string('image')->nullable();
            $table->text('keterangan')->nullable(); 
            $table->timestamps();
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
