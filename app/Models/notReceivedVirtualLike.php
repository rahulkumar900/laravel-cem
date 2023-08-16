<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notReceivedVirtualLike extends Model
{
    use HasFactory;

    protected $table = 'not_received_virtuaLikes';
    
    protected $fillable = [
        'id',
        'is_lead',
        'user_id',
        'created_at',
        'updated_at',
        'last_attempted'
    ];
}
