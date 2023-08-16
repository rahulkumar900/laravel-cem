<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReligionController extends Controller
{
    // get all religion
    public function getAllReligion()
    {
        $religion = Cache::rememberForever('religion_list', function () {
            return Religion::allReligion()->toArray();
        });
        return response()->json($religion);
    }
}
