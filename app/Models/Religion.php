<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\FunctionLike;

class Religion extends Model
{
    use HasFactory;
    protected $table = 'religion_mapping';

    public static function allReligion(){
        return Religion::get();
    }
}
