<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name','province_id','county_id','created_by','edited_by','deleted_by','deleted_at'];

    public $timestamps = false;

    protected $with = ['province', 'county'];

    public function province() {
        return $this->hasOne(Province::class, 'id', 'province_id')->orderBy('name');
    }

    public function county() {
        return $this->hasOne(County::class, 'id', 'county_id')->orderBy('name');
    }
}
