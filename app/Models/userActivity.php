<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userActivity extends Model
{
    use HasFactory;

    protected $table = 'userActivities';
    protected $fillable = [
        'user_id',
        'is_lead',
        'D1',
        'D2',
        'D3',
        'D4',
        'D5',
        'D6',
        'D7',
        'W1',
        'W2',
        'W3',
        'W4',
        'W5',
        'W6',
        'M1',
        'M2',
        'M3',
        'AD1',
        'AD2',
        'AD3',
        'AD4',
        'AD5',
        'AD6',
        'AD7',
        'AW1',
        'AW2',
        'AW3',
        'AW4',
        'AW5',
        'AW6',
        'AM1',
        'AM2',
        'AM3',
    ];
}
