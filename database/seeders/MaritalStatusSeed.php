<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaritalStatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create family type
        DB::table('family_type_mappings')->insert([
            [
                "id"            => 1,
                "mapping_id"    => 1,
                "name"          => "Nuclear"
            ],
            [
                "id"            => 2,
                "mapping_id"    => 2,
                "name"          => "Joint"
            ]
        ]);


        // create family type
        DB::table('religion_mapping')->insert([
            [
                "id" => 1,
                "religion" => "Hindu",
                "mapping_id" => 1
            ],
            [
                "id" => 2,
                "religion" => "Sikh", "
                mapping_id" => 1
            ],
            [
                "id" => 3,
                "religion" => "Jain", "
                mapping_id" => 1
            ],
            [
                "id" => 4,
                "religion" => "Muslim",
                "mapping_id" => 2
            ],
            [
                "id" => 5,
                "religion" => "Christian",
                "mapping_id" => 3
            ],
            [
                "id" => 6,
                "religion" => "Buddhist",
                "mapping_id" => 4
            ],
            [
                "id" => 7,
                "religion" => "Bahai",
                "mapping_id" => 5
            ],
            [
                "id" => 8,
                "religion" => "Jewish",
                "mapping_id" => 6
            ],
            [
                "id" => 9,
                "religion" => "Parsi",
                "mapping_id" => 7
            ]
        ]);

        // house type maoping
        DB::table('house_type_mappings')->insert([
            [
                "id"  => 1,
                "mapping_id"  => 1,
                "name"    => "Owned"
            ],
            [
                "id"  => 2,
                "mapping_id"  => 2,
                "name"    => "Rented"
            ],
            [
                "id"  => 3,
                "mapping_id"  => 3,
                "name"    => "Leased"
            ]
        ]);

        // parent occupation
        DB::table('parrent_occupation_mappings')->insert([
            [
                "id"           => 1,
                "mapping_id"   => 0,
                "name"         => "Not Alive"
            ],
            [
                "id"           => 2,
                "mapping_id"   => 1,
                "name"         => "Business/Self Employed"
            ],
            [
                "id"           => 3,
                "mapping_id"   => 2,
                "name"         => "Doctor"
            ],
            [
                "id"           => 4,
                "mapping_id"   => 3,
                "name"         => "Government Job"
            ],
            [
                "id"           => 5,
                "mapping_id"   => 4,
                "name"         => "Teacher"
            ],
            [
                "id"           => 6,
                "mapping_id"   => 5,
                "name"         => "Private Job"
            ],
            [
                "id"           => 7,
                "mapping_id"   => 6,
                "name"         => "Not Working"
            ],
            [
                "id"           => 8,
                "mapping_id"   => 7,
                "name"         => "Other"
            ],
            [
                "id"           => 9,
                "mapping_id"   => 8,
                "name"         => "Retired"
            ]
        ]);

        // mrital status mapping
        DB::table('marital_status_mappings')->inser([
            [
                "id"                => 1,
                "marital_status_id" => 1,
                "name"              => "Never Married"
            ],
            [
                "id"                => 2,
                "marital_status_id" => 2,
                "name"              => "Married"
            ],
            [
                "id"                => 3,
                "marital_status_id" => 3,
                "name"              => "Awaiting Divorce"
            ],
            [
                "id"                => 4,
                "marital_status_id" => 4,
                "name"              => "Divorcee"
            ],
            [
                "id"                => 5,
                "marital_status_id" => 5,
                "name"              => "Widowed"
            ],
            [
                "id"                => 6,
                "marital_status_id" => 6,
                "name"              => "Anulled"
            ],
            [
                "id"                => 7,
                "marital_status_id" => 7,
                "name"              => "Doesn't Matter"
            ]
        ]);

        // manglik mapping
        DB::table('manglik_mappings')->insert([
            [
                "id"        => 1,
                "mapping_id" => 1,
                "name"      => "Manglik"
            ],
            [
                "id"        => 2,
                "mapping_id" => 2,
                "name"      => "Non-Manglik"
            ],
            [
                "id"        => 3,
                "mapping_id" => 3,
                "name"      => "Anshik Manglik"
            ],
            [
                "id"        => 4,
                "mapping_id" => 4,
                "name"      => "Don't Know"
            ],
            [
                "id"        => 5,
                "mapping_id" => 5,
                "name"      => "Doesn't Matter"
            ]
        ]);

        // food choice mapping
        DB::table('foodchoice_mappings')->insert([
            [
                "id"          => 1,
                "mapping_id"  => 0,
                "name"        => "Doesn't Matter"
            ],
            [
                "id"          => 2,
                "mapping_id"  => 1,
                "name"        => "Vegetarian"
            ],
            [
                "id"          => 3,
                "mapping_id"  => 2,
                "name"        => "Non-Vegetarian"
            ]
        ]);

        // relation mapping
        DB::table('relation_mappings')->insert([
            [
                "id"            => 1,
                "mapping_id"    => 1,
                "name"          => "Myself"
            ],
            [
                "id"            => 2,
                "mapping_id"    => 2,
                "name"          => "Mother"
            ],
            [
                "id"            => 3,
                "mapping_id"    => 3,
                "name"          => "Father"
            ],
            [
                "id"            => 4,
                "mapping_id"    => 4,
                "name"          => "Sister"
            ],
            [
                "id"            => 5,
                "mapping_id"    => 5,
                "name"          => "Brother"
            ],
            [
                "id"            => 6,
                "mapping_id"    => 6,
                "name"          => "Son"
            ],
            [
                "id"            => 7,
                "mapping_id"    => 7,
                "name"          => "Daughter"
            ],
            [
                "id"            => 8,
                "mapping_id"    => 8,
                "name"          => "Other"
            ]
        ]);

        // gender mapping
        DB::table('gender_mappings')->insert([
            [
                "id"        => 1,
                "mapping_id"=> 1,
                "name"      => "Male"
            ],
            [
                "id"        => 2,
                "mapping_id"=> 2,
                "name"      => "Female"
            ]
        ]);

        // occupation mapping
        DB::table('occupation_mappings')->insert([
                [
                    "id"        => 1,
                    "mapping_id"=> 1,
                    "name"      => "Business/Self Employed"
                ],
                [
                    "id"        => 2,
                    "mapping_id"=> 2,
                    "name"      => "Doctor"
                ],
                [
                    "id"        => 3,
                    "mapping_id"=> 3,
                    "name"      => "Government Job"
                ],
                [
                    "id"        => 4,
                    "mapping_id"=> 4,
                    "name"      => "Teacher"
                ],
                [
                    "id"        => 5,
                    "mapping_id"=> 5,
                    "name"      => "Private Job"
                ],
                [
                    "id"        => 6,
                    "mapping_id"=> 6,
                    "name"      => "Not Working"
                ],
                [
                    "id"        => 7,
                    "mapping_id"=> 7,
                    "name"      => "Other"
                ]
        ]);
    }
}
