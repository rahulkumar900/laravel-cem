<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";

    protected $fillable = ["id_number", "temple_id", "order_date", "order_list", "name"];

    protected static function sendProfileList($user_id)
    {
        return Order::where("id_number", $user_id)->get();
    }

    protected static function saveSendList($user_id, $user_list, $temple_id)
    {
        return Order::create([
            "id_number"     =>      $user_id,
            "temple_id"     =>      $temple_id,
            "order_date"    =>      date('Y-m-d'),
            "order_list"    =>      $user_list,
            "name"          =>      "N.A"
        ]);
    }

    protected static function sentProfileList($user_id)
    {
        return Order::where('id_number', $user_id)->orderBy('order_date','desc')->get();
    }
}
