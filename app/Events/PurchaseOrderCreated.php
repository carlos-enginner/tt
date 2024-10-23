<?php

namespace App\Events;

use App\Jobs\Payment\PaymentOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct($data)
    {
        \Log::info("Created");
        \Log::info($data);
        // Enfileirar o job na fila purchase_order
        PaymentOrder::dispatch($data)->onQueue('payment_order');
    }
}
