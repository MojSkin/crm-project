<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'project_type_id',
        'user_id',
        'user_percentage',
        'city_id',
        'region',
        'main_street',
        'aux1',
        'aux2',
        'alley1',
        'alley2',
        'address',
        'lat',
        'long',
        'po_code',
        'blocks',
        'units',
        'floors',
        'user_id'
    ];

    public function files(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('created_at', 'desc')->whereType('PROJECT_FILES');
    }

    public function covers(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('created_at', 'desc')->whereType('PROJECT_COVER');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('created_at', 'desc');
    }

    public function comments():MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('comment_id')->with(['comments', 'user'])->orderBy('created_at', 'desc');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ProjectNote::class)->orderBy('created_at');
    }

    public function lastNote()
    {
        return $this->notes()->first() ?? null;
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project_type(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(ContactProject::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
