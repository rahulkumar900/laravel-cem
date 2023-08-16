<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLead extends Model
{
    use HasFactory;

    protected $table = 'requestLeads';

    protected $fillable = ['id',' temple_id', 'request_id'];

    public static function checkPreviousRequestedLead($temple_id)
    {
        return RequestLead::where('temple_id',$temple_id)->first();
    }

    public static function updateRequestLead($temple_id, $request_type)
    {
        RequestLead::updateOrCreate(
            ['temple_id' => $temple_id],
            [
                'temple_id' => $temple_id,
                'request_id' => $request_type
            ]
        );
    }
}
