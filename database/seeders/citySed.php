<?php

namespace Database\Seeders;

use App\Models\CityLists;
use Illuminate\Database\Seeder;
use File;
class citySed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = resource_path() . "/master_jsons/cities.json";
        $city_aray = json_decode(File::get($cities), true);
        $create_city= CityLists::insert($city_aray);
    }
}
