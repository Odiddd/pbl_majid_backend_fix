<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class informasiModel extends Model
{
    use HasFactory;
    protected $table = "informasi";
    protected $primaryKey = "informasi_id";

    protected $fillable =
    [
        'informasi_id',
        'judul',
        'isi',
        'status',
        'image',
        'keterangan',
    ];
}
