<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kegiatanModel extends Model
{
    use HasFactory;

    protected $table = "kegiatan";
    protected $primaryKey = "kegiatan_id";

    protected $fillable = [
        'kegiatan_id',
        'nama_kegiatan',
        'isi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'status',
        'image',
        'keterangan',
    ];
}
