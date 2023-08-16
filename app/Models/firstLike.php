<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class firstLike extends Model
{
    use HasFactory;

    protected $table = 'first_likes';
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
        'dayPlus',
        'NoDay',
        'is_paid',
        'gender',
        'analytic_date'
    ];
}
