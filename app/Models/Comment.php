<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commentable_id',
        'comment_id',
        'commentable_type',
        'comment',
    ];

    protected $with = ['reply_to', 'user'];

    public static function make($record) {
        return self::updateOrcreate(
            ['id' => $record['id'] ?? null],
            [
                'user_id' => Auth::id(),
                'commentable_id' => $record['commentable_id'],
                'commentable_type' => $record['commentable_type'],
                'comment' => $record['comment'],
                'comment_id' => $record['comment_id'] ?? $record['reply_to'] ?? null,
            ]);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function reply_to()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
