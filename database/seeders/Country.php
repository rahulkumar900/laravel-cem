<?php

namespace Database\Seeders;

use App\Models\Country as ModelsCountry;
use Illuminate\Database\Seeder;
use File;

class Country extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countrys = resource_path() . "/master_jsons/countries.json";
        $country_aray = json_decode(File::get($countrys), true);
        $create_country = ModelsCountry::insert($country_aray);
    }
}
