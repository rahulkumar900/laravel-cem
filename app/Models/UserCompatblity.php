<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompatblity extends Model
{
    use HasFactory;

    protected $table = "userCompatibilities";

    protected $fillable = ["user_data_id", "credit_available", "amount_collected"];

    
}
