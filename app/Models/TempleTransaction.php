<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TempleTransaction extends Model
{
    use HasFactory;

    protected $table = 'temple_transaction_logs';

    protected $fillable = ["invoice_id", "debit_amt", "credit_amt", "rec_temple_id", "handover_to", "narration", "closing_balance"];

    //save data into temple transation
    public static function saveTrLogs($invoice_id, $debit_amount, $credit_amount, $rec_temple_id, $hand_over_to, $narration, $balance, $type)
    {

        // if ($type != "cash") {
        //     $crete_credit = TempleTransaction::create([
        //         "invoice_id"            =>      $invoice_id,
        //         "debit_amt"             =>      0,
        //         "credit_amt"            =>      $debit_amount,
        //         "rec_temple_id"         =>      $rec_temple_id,
        //         "handover_to"           =>      $hand_over_to,
        //         "narration"             =>      "cash diretly transfered into company account",
        //         "closing_balance"       =>      (($balance - $debit_amount) - $credit_amount)
        //     ]);
        // }

        $creat_trsnsaction =  TempleTransaction::create([
            "invoice_id"            =>      $invoice_id,
            "debit_amt"             =>      $debit_amount,
            "credit_amt"            =>      $credit_amount,
            "rec_temple_id"         =>      $rec_temple_id,
            "handover_to"           =>      $hand_over_to,
            "narration"             =>      $narration,
            "closing_balance"       =>      (($balance+ $debit_amount)-$credit_amount)
        ]);

        return $creat_trsnsaction;
    }

    // get user balance
    public static function getUserOpeningBalance($temple_id)
    {
        return TempleTransaction::where('rec_temple_id', $temple_id)->select(DB::raw("(SUM(debit_amt) - SUM(credit_amt)) as closing_balance"))->get();
    }

    //get user transactions
    public static function getTempleTransactions($temple_id)
    {
        return TempleTransaction::where('rec_temple_id',$temple_id)->get(['id', 'created_at', 'credit_amt', 'debit_amt', 'invoice_id', 'narration', 'closing_balance']);
    }
}
