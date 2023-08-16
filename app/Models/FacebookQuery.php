<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookQuery extends Model
{
    use HasFactory;
    protected $table = 'facebook_query';
    protected $fillable = ['call_count', 'creation_date_time', 'created_at', 'updated_at', 'relation_name'];

    public static function getFacebookQuery()
    {
        return FacebookQuery::get();
    }

    public static function editFacebookQuery($id, $call_count, $days, $rel_name)
    {
        return FacebookQuery::where('id',$id)->update([
            'call_count'           =>      $call_count,
            'creation_date_time'    =>      $days,
            'relation_name'         =>      $rel_name
        ]);
    }
}
