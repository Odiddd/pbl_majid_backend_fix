<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenisTransaksiModel extends Model
{
    use HasFactory;
    protected $table = "jenis_transaksi";
    protected $primaryKey = "jenis_transaksi_id";

    protected $fillable =
    [
        'jenis_transaksi_id',
        'jenis_name',
    ];

    public function transaksi()
    {
        return $this->hasMany(transaksiModel::class, 'jenis_transaksi_id', 'jenis_transaksi_id');
    }
}
