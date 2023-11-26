<?php

namespace App\Models;

use App\Events\AlarmEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Alarm extends Model
{
    use HasFactory;

    protected $dispatchesEvents = [
        'created' => AlarmEvent::class,
    ];

    protected $fillable = [
        'user_id',
        'alarmable_id',
        'alarmable_type',
        'title',
        'description',
        'weekdays',
        'alarm_date',
        'alarm_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function alarmable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
