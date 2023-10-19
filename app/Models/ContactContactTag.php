<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactContactTag extends Model
{
    use HasFactory;
    protected $table = 'contact_contact_tag';
    protected $fillable = ['contact_id', 'contact_tag_id'];
}
