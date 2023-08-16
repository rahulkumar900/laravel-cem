<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadValue extends Model
{
    use HasFactory;

    protected $table = 'lead_values';

    public static function calculateLeadValue($gender,$relation,$income){
        //dd($gender . ' | ' . $relation.' | '. $income);
        //SELECT * FROM `lead_values` WHERE relation = 'myself' AND gender = 'male' AND min_range < 8 AND max_range >8
        $lead_value = LeadValue::where(['gender'=>$gender,'relation'=>$relation])->where('min_range','<',$income)->where('max_range','>',$income)->select('lead_value')->first();

        $ret_ld_val = '';
        if(empty($lead_value)){
            $ret_ld_val = 0;
        }else{
            $ret_ld_val = $lead_value->lead_value;
        }
        return $ret_ld_val;
    }
}
