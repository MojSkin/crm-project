<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedFormDetail extends Model
{
    use HasFactory;

    protected $fillable = ['form_id', 'form_field_id', 'value'];
    protected $with = ['field'];

    public function field()
    {
        return $this->hasOne(FormField::class, 'id', 'form_field_id');
    }
}
