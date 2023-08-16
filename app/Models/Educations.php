<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Educations extends Model
{
    use HasFactory;

    protected $table = 'educations';


    // get all educations with cache remember forever function if new qualifications added then clear cache to remember new data
    public static function getEducationList()
    {
        return Cache::rememberForever('getAllEducations', function () {
            return Educations::get();
        });
    }
}
