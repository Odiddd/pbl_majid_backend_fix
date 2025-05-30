<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksiModel extends Model
{
    use HasFactory;

    protected $table = "transaksi";
    protected $primaryKey = "transaksi_id";

    protected $fillable = [
        'transaksi_id',
        'kategori',
        'jenis_transaksi_id',
        'nominal',
        'sumber',
        'mengetahui',
        'status',
        'keterangan',
    ];

    public function jenis_transaksi()
{
    return $this->belongsTo(jenisTransaksiModel::class, 'jenis_transaksi_id', 'jenis_transaksi_id');
}

}