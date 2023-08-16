<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activeUser extends Model
{
    use HasFactory;

    protected $table = 'active_users';
    
    protected $fillable = [
        'DAU',
        'day'
    ];
}
