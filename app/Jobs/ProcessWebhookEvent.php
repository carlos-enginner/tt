<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\PaymentReceived;
use Illuminate\Support\Arr;

class ProcessWebhookEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $eventData;

    public function __construct($eventData)
    {
        $this->eventData = $eventData;
    }

    public function handle()
    {
        $paymentReceived = Arr::get($this->eventData, 'event', null);
        if ($paymentReceived === 'PAYMENT_RECEIVED') {
            broadcast(new PaymentReceived());
        }
    }
}
