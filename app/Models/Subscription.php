<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['profile_id', 'email', 'contact', 'customer_id', 'order_id', 'amount', 'token_id', 'payment_id', 'last_payment_at', 'stopped'];
}
