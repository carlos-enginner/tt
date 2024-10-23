<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $eventData = $request->all();

        ProcessWebhookEvent::dispatch($eventData);

        return response()->json(['status' => 'success']);
    }
}
