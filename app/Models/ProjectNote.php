<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProjectNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'note',
        'project_status_id',
        'project_result_id',
        'alarm_id',
    ];

    protected $with = ['project_status', 'project_result', 'user', 'alarms'];


    public function project_status(): BelongsTo
    {
        return $this->belongsTo(ProjectStatus::class);
    }

    public function project_result(): BelongsTo
    {
        return $this->belongsTo(ProjectResult::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function alarms():MorphMany
    {
        return $this->morphMany(Alarm::class, 'alarmable')->orderBy('created_at', 'ASC');
    }
}
