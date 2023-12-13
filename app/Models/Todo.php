<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'todoable_id',
        'todoable_type',
        'title',
        'description',
        'due_date',
        'flag',
        'is_done',
        'user_id',
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'due_date' => 'datetime:Y/m/d'
    ];

    public $flags = [
        'کم',
        'متوسط',
        'عادی',
        'زیاد',
        'اضطراری',
    ];

    public function todoable(): MorphTo
    {
        return $this->morphTo();
    }

    public function alarm(): MorphOne
    {
        return $this->morphOne(Alarm::class, 'alarmable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
