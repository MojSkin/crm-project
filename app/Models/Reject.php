<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reject extends Model
{
    use HasFactory;

    protected $fillable = ['user', 'comment', 'status', 'rejectable_id', 'rejectable_type'];

    public function rejectable() {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
