<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VLLogs extends Model
{
    use HasFactory;
    protected $table = 'vl_logs';
    
    protected $fillable = [
        'count',
        'execution_type',
        'created_at',
        'updated_at',
    ];
}
