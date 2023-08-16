<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityLists extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = ['id', 'name', 'state_id'];

    public function statename()
    {
        return $this->hasOne(State::class, "id", "state_id");
    }

    // get city name
    public static function getCityName($city_name)
    {
        return CityLists::join('states', 'states.id','cities.state_id')->join('countries', 'countries.id', 'states.country_id')->where('cities.name','like',$city_name."%")->orderBy('cities.name','asc')->take(5)->get(['cities.id', 'cities.name as city', 'states.name as state', 'countries.name as country']);
    }
}
