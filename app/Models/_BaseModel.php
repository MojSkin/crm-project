<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _BaseModel extends Model
{
    use HasFactory;

    public static function getDefault($arrayName) {
        $self = new static;
        return $self->$arrayName;
    }

    public function rejects() {
        return $this->morphMany(Reject::class, 'rejectable')->orderBy('created_at', 'desc')->with('user');
    }

    public function rejected() {
        return $this->morphMany(Reject::class, 'rejectable');
    }

    public function images() {
        return $this->morphMany(Image::class, 'imageable')->orderBy('created_at', 'desc');
    }
}
