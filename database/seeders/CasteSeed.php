<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Caste;
use File;

class CasteSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $castes = resource_path() . "/master_jsons/castes.json";
        $caste_aray = json_decode(File::get($castes), true);
        $create_caste = Caste::insert($caste_aray);
    }
}
