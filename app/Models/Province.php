<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name','created_by','edited_by','deleted_by','deleted_at'];

    public $timestamps = false;

    public function counties()
    {
        return $this->hasMany(County::class);
    }

    public function childrenRecursive()
    {
        return $this->hasMany(County::class)->select(DB::raw('id, CONCAT("cn_", id) as new_id, name, province_id'))->orderBy('name')->with('childrenRecursive');
    }
}
