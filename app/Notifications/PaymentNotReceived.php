<?php

namespace App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaymentNotReceived implements ShouldBroadcast
{
    use Queueable;

    public const STATUSES = 'not_received';
    public $message;
    protected $data;

    public function __construct($message, $data = [])
    {
        $this->message = $message;
        $this->data = $data;
    }

    public function broadcastAs()
    {
        return 'payment_not_received';
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
            'data' => $this->data,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
