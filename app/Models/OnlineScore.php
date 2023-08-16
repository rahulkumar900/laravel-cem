<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineScore extends Model
{
    use HasFactory;

    protected $table = 'onlineScores';
    protected $fillable = [
        'user_id',
        'is_lead',
        'OD1',
        'OD2',
        'OD3',
        'OD4',
        'OD5',
        'OD6',
        'OD7',
        'OW1',
        'OW2',
        'OW3',
        'OW4',
        'OW5',
        'OW6',
        'OM1',
        'OM2',
        'OM3',
    ];
}
