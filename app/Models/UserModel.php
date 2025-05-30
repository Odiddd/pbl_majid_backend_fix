<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class userModel extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = "users";
    protected $primaryKey = "user_id";

    protected $fillable =
    [
        // 'user_id',
        'role_id',
        'name',
        'email',
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(roleModel::class, 'role_id', 'role_id');
    }

}
