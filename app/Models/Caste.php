<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Caste extends Model
{
    use HasFactory;

    protected $table = 'castes';

    protected $fillable = ["value", "marriage_mapping", "religion_id"];

    public function religion()
    {
        return $this->hasOne(Religion::class, 'id', 'religion_id');
    }

    // get all castes
    protected static function getAllCaste()
    {
            return Caste::with('religion')->get();
    }

    protected static function matchCaste($caste_name)
    {
      return  $caste_match = Caste::whereRaw("LOWER(value)='$caste_name'")->first();
    }

    protected static function matchCasteWReligion($caste_name, $religion_id)
    {
       return $caste_match = Caste::whereRaw("LOWER(value)='$caste_name' AND religion_id =$religion_id")->first();
    }

    // get caste details by name
    protected static function getCasteDetails($caste_name)
    {
        return Caste::where(['value'=>$caste_name])->first();
    }
}
