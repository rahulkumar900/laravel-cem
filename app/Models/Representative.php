<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    protected $fillable = [
        'name', 'email', 'temple_id', 'type', 'mobile', 'photo', 'off_day', 'check_in_time', 'late_limit', 'v_late_limit', 'w_hours', 'min_w_hours', 'min_cash_in_hand', 'temple_carousel'
    ];
}
