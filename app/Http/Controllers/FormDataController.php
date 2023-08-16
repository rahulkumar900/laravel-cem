<?php

namespace App\Http\Controllers;

use App\Models\Caste;
use App\Models\CityLists;
use App\Models\Educations;
use Illuminate\Http\Request;

class FormDataController extends Controller
{
    // get all education lists
    public function getAllEducations()
    {
        return response()->json(Educations::getEducationList());
    }

    // search city and get lists
    public function getAllCities(Request $request)
    {
        return response()->json(CityLists::getCityName($request->city_name));
    }

    // get all castes
    public function getAllCastes()
    {
        return response()->json(Caste::getAllCaste());
    }
}
