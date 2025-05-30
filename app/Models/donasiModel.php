<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class donasiModel extends Model
{
    use HasFactory;

    protected $table = "donasi";
    protected $primaryKey = "donasi_id";

    protected $fillable = [
        'donasi_id',
        'name',
        'kontak',
        'nominal',
        'status_pembayaran',
        'midtrans_order_id',
        'masuk_transaksi',
    ];
}
