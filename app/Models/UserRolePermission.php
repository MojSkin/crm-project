<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermission extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_role_id',
        'action'
    ];

    public function role()
    {
        return $this->belongsTo(UserRole::class);
    }
}
