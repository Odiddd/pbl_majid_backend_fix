<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class UserModel extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = "users";
    protected $primaryKey = "user_id";

    protected $fillable =
    [
        'user_id',
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
