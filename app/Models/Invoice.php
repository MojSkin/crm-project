<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'transactionId',
        'gateway',
        'status',
        'callbackId',
        'userCardNo',
        'amount',
        'target',
        'target_id',
        'details'
    ];

    protected $casts = [
        'details' => 'json'
    ];
}
