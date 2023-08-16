<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferLead extends Model
{
    use HasFactory;

    protected $fillable = ['transfered_from', 'transfered_to', 'transfered_by', 'transfered_on', 'transfered_leads'];


    protected static function saveRecord($transfer_from, $transfer_to, $tranfer_by, $leads)
    {
        return TransferLead::create([
            'transfered_from'       =>      $transfer_from,
            'transfered_to'         =>      $transfer_to,
            'transfered_by'         =>      $tranfer_by,
            'transfered_on'         =>      date('Y-m-d H:i:s'),
            'transfered_leads'      =>      $leads
        ]);
    }
}
