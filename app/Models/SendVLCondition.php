<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendVLCondition extends Model
{
    use HasFactory;

    protected $table = 'send_vl_condition';
    
    protected $fillable = [
        'status',
        'created_at',
        'updated_at',
    ];
}
