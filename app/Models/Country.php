<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Country extends Model
{
    use HasFactory;

    protected $table= 'countries';

    protected $fillble = ["sortname", "name", "phonecode"];

    // get all country data
    protected static function getAllCountries()
    {
        return Cache::rememberForever('countryNames', function () {
            return Country::all();
        });
    }
}
