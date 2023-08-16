<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'token', 'blank_lead_handler', 'account_id', 'campaign_country', 'type'];

    protected $appends = ['access_token'];

    public function getAccessTokenAttribute()
    {
        if ($this->account_id) {
            $token = AdAccount::where('id', $this->account_id)->first()->token;
            return $token;
        }
        return null;
    }
}
