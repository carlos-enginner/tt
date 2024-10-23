<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrders extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_orders_id',
        'payment_transaction_metadata'
    ];
}
