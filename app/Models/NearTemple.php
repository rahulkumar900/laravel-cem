<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearTemple extends Model
{
    use HasFactory;

    protected $table = 'near_temple_mapping';

    protected $fillable = ['id', 'temple_id', 'near_temple', 'updated_at', 'created_at'];
}
