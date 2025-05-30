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
        Schema::create('donasi', function (Blueprint $table) {
            $table->id('donasi_id');
            $table->string('name')->nullable()->default('hamba Allah');
            $table->string('kontak')->nullable();
            $table->double('nominal');
            $table->enum('status_pembayaran', ['pending', 'success', 'failed'])->default('pending');
            $table->string('midtrans_order_id')->nullable();
            $table->boolean('masuk_transaksi')->default(false);
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
