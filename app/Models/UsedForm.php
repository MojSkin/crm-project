<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedForm extends _BaseModel
{
    use HasFactory;

    public $statuses = [
        0 => ['text' => 'منتظر بررسی', 'class' => 'tag is-rounded is-solid'],
        2 => ['text' => 'نیازمند تصحیح', 'class' => 'tag is-rounded is-warning'],
        10 => ['text' => 'تایید جهت مصاحبه', 'class' => 'tag is-rounded is-success is-outlined'],
        11 => ['text' => 'تایید نهایی', 'class' => 'tag is-rounded is-success'],
        90 => ['text' => 'رد شده', 'class' => 'tag is-rounded is-danger'],
        98 => ['text' => 'سایر', 'class' => 'tag is-rounded is-dark'],
        99 => ['text' => 'نامشخص', 'class' => 'tag is-rounded is-danger is-outlined'],
    ];

    protected $fillable = ['date', 'form_id', 'user_id', 'user_ip', 'transaction'];
    protected $with = ['invoice'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function details()
    {
        return $this->hasMany(UsedFormDetail::class, 'form_id', 'id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'target_id', 'id')->whereTarget('form');
    }
}
