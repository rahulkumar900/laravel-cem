<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use File;

class StateSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = resource_path() . "/master_jsons/states.json";
        $state_aray = json_decode(File::get($states), true);
        $create_state = State::insert($state_aray);
    }
}
