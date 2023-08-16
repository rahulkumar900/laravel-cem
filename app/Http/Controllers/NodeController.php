<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NodeController extends Controller
{
    /**
     * religion mapping  store data forever
     */

    public function storeMappedData()
    {
        $religion =  Cache::rememberForever('religions', function () {
            return DB::table('religion_mapping')->get()->toArray();
        });
        return response()->json(DB::table('religion_mapping')->get()->toArray());
    }

    /**
     * Relation mapping store daa forever in canche
     */
    public function relationMapping()
    {
        /*$relation = Cache::rememberForever('relation', function () {
            return DB::table('relation_mappings')->get();
        });*/
        return response()->json(DB::table('relation_mappings')->get());
    }

    /**
     * caste lists stores data only daily
     */
    public function casteList()
    {
        /*$caste_lsit =Cache::remember('castelist',60*60*24 ,function () {
            return DB::table('castes')->get();
        });*/
        return response()->json(DB::table('castes')->get());
    }

    /**
     * Education data stores data once a day
     */
    public function educationList()
    {
        /* $education = Cache::remember('educations',60*60*24, function(){
            return DB::table('educations')->get();
        });*/
        return response()->json(DB::table('educations')->get());
    }

    /**
     * occupation stores data for forever
     */
    public function occupationList()
    {
        /*$occupations = Cache::rememberForever('occupationlist', function(){
            return DB::table('occupation_mappings')->get();
        });*/
        return response()->json(DB::table('occupation_mappings')->get());
    }

    /**
     * Parent occupation stores data for forever
     */
    public function parentOccupations()
    {
        /* $parent_occupation = Cache::rememberForever('parentoccupation', function(){
            return DB::table('parrent_occupation_mappings')->get();
        });*/
        return response()->json(DB::table('parrent_occupation_mappings')->get());
    }

    /**
     * maglik status data stores for forever
     */
    public function manglikStatus()
    {
        /*$matglik_status = Cache::rememberForever('manglikstatus', function(){
            return DB::table('manglik_mappings')->get();
        });*/
        return response()->json(DB::table('manglik_mappings')->get());
    }

    /**
     * marital status stores data once
     */
    public function maritalStatus()
    {
        /*$marital_status = Cache::rememberForever('maritalstatus', function(){
            return DB::table('marital_status_mappings')->get();
        });*/
        return response()->json(DB::table('marital_status_mappings')->get());
    }

    /* get qualification by id */
    public function qualificationById(Request $request)
    {
        $qualification = DB::table('educations')->where('id', $request->qualification)->first(['id', 'degree_name']);
        return response()->json($qualification);
    }

    // get all countries
    public function getAllCountries()
    {
        return response()->json(Country::getAllCountries());
    }
}
