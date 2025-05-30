<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservasiModel extends Model
{
    use HasFactory;

    protected $table = "reservasi";
    protected $primaryKey = "reservasi_id";

    protected $fillable = [
        'reservasi_id',
        'nama_pemesan',
        'kontak_pemesan',
        'tempat_reservasi_id',
        'nama_acara',
        'tanggal_acara',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah_tamu',
        'status_reservasi',
        'mengetahui',
        'tagihan',
        'status_pembayaran',
        'keterangan',
        'masuk_transaksi',
    ];

    public function tempatReservasi()
    {
        return $this->belongsTo(tempatReservasiModel::class, 'tempat_reservasi_id', 'tempat_reservasi_id');
    }
}
