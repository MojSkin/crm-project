<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    use HasFactory;

    protected $fillable = ['name','province_id','created_by','edited_by','deleted_by','deleted_at'];

    public $timestamps = false;

    public function province() {
        return $this->hasOne(Province::class, 'id', 'province_id')->orderBy('name');
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function childrenRecursive()
    {
        return $this->hasMany(City::class)->orderBy('name');
    }

}
