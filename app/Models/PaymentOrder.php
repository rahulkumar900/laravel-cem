<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PaymentOrder extends Model
{
    use HasFactory;

    protected $table = "payment_orders";

    protected $fillable = ["type", "order_id", "customer_id", "customer_mobile", "name", "assign_to", "assign_by", "amount", "order_from", "txn_id", "txn_date", "status", "error_code", "narration","plan_id", "payment_by", "discount", "plan_amount"];

    // save data into payment order
    public static function savePaymentRecord($type, $order_id, $user_id, $assign_to, $assign_by, $paid_amount, $order_from, $invoice_id, $txn_date, $plan_id, $login_temple, $discount, $plan_amount, $customer_mobile)
    {
        return PaymentOrder::create([
            "type"              =>      $type,
            "order_id"          =>      $order_id,
            "customer_id"       =>      $user_id,
            "assign_to"         =>      $assign_to,
            "assign_by"         =>      $assign_by,
            "amount"            =>      $paid_amount,
            "order_from"        =>      $order_from,
            "txn_id"            =>      $invoice_id,
            "txn_date"          =>      $txn_date,
            "status"            =>      'success',
            "error_code"        =>      '',
            "narration"         =>      "payment received by $assign_to",
            "plan_id"           =>      $plan_id,
            "payment_by"        =>      $login_temple,
            "discount"          =>      $discount,
            "plan_amount"       =>      $plan_amount,
            "customer_mobile"   =>      $customer_mobile
        ]);
    }
}
