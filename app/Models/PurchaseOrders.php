<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrders extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_name',
        'consumer_id',
        'purchase_description',
        'purchase_value',
        'payment_method',
        'payment_metadata',
        'consumer_transaction_metadata',
        'charge_transaction_metadata'
    ];
}
