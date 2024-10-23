<?php

namespace App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaymentReceived implements ShouldBroadcast
{
    use Queueable;

    public const STATUSES = 'success';
    public $message;

    public function __construct()
    {
        $this->message = 'Pagamento recebido com sucesso!';
    }

    public function broadcastAs()
    {
        return 'payment_received';
    }

    public function broadcastOn(): Channel
    {
        return new Channel('notifications');
    }

    public function broadcastWith()
    {
        return [
            'status' => self::STATUSES,
            'message' => $this->message,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
