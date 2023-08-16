<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseUser extends Model
{
    use HasFactory;

    protected $table = 'franchise_users';

    protected $fillable = [
        'name',
        'mobile',
        'location',
    ];
}
