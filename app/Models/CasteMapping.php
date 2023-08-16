<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasteMapping extends Model
{
    use HasFactory;
    protected $table = 'caste_mappings';

    // get caste list
    public static function getCasteLists($caste_id)
    {
        return CasteMapping::where(['mapping_id'=>$caste_id])->get();
    }
}
