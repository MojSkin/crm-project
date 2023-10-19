<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactProject extends Model
{
    use HasFactory;

    protected $table = 'contact_project';
    protected $with = ['position', 'contact'];
    protected $fillable = [
        'contact_id',
        'contact_position_id',
        'project_id',
    ];

    public function position()
    {
        return $this->belongsTo(ContactPosition::class, 'contact_position_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

}
