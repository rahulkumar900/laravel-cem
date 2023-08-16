<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncompleteHiringLeads extends Model
{
    use HasFactory;

    protected $table = 'incomplete_hiring_leads';

    public function getCallTimeAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y h:i:s A');
    }
}
