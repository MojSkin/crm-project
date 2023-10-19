<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_id',
        'name',
        'title',
        'hint',
        'description',
        'placeholder',
        'type',
        'default',
        'is_required',
        'is_printable',
        'min',
        'max',
        'steps',
        'max_rows',
        'options',
        'validator',
        'tab_index',
        'col_tablet',
        'col_desktop',
        'col_widescreen',
        'custom_class',
        'custom_id',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_printable' => 'boolean',
    ];

    protected function validator(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => explode('|', $value ?? ''),
        );
    }
}
