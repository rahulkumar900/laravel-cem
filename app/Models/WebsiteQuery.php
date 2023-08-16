<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteQuery extends Model
{
    use HasFactory;
    protected $table = 'website_query';
    protected $fillable = ['call_count', 'creation_date_time', 'created_at', 'updated_at'];

    public static function getWebQuery()
    {
        return WebsiteQuery::get();
    }

    public static function editWebsiteQuery($id, $call_count, $days, $rel_name)
    {
        return WebsiteQuery::where('id', $id)->update([
            'call_count'           =>      $call_count,
            'creation_date_time'    =>      $days,
            'relation_name'         =>      $rel_name
        ]);
    }
}
