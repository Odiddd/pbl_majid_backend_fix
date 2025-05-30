<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatReservasiModel extends Model
{
    use HasFactory;

    protected $table = 'tempat_reservasi';
    protected $primaryKey = 'tempat_reservasi_id';

    protected $fillable = [
        'lokasi',
        'kapasitas',
        'image',
        'keterangan',
        'biaya',
    ];

    public function reservasi()
    {
        return $this->hasMany(ReservasiModel::class, 'tempat_reservasi_id', 'tempat_reservasi_id');
    }
}
