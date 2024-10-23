<?php

namespace App\Jobs\Payment;

use App\Exceptions\PaymentNotReceivedException;
use App\Notifications\PaymentNotReceived;
use App\Notifications\PaymentReceived;
use App\Services\Integrations\Asaas\HttpRequestService;
use App\Services\Integrations\Asaas\PaymentService;
use App\Services\Payment\PaymentOrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Arr;
use LogicException;

class PaymentOrder implements ShouldQueue
{
    use Queueable;

    protected $paymentOrderService;
    protected $chargeData;
    protected $paymentService;

    /**
     * Create a new job instance.
     */
    public function __construct($chargeData)
    {
        $this->paymentOrderService = new PaymentOrderService();
        $this->paymentService = new PaymentService(new HttpRequestService());
        $this->chargeData = $chargeData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            \Log::info("PaymentOrder::handle");
            \Log::info($this->chargeData);

            $purchaseOrderId = Arr::get($this->chargeData, "purchase_orders_id", 0);
            if ($purchaseOrderId <= 0) {
                throw new LogicException(
                    "Purchase order id not valid"
                );
            }

            $purchaseOrder = $this->paymentService->getPurchaseOrder($purchaseOrderId);
            if (empty($purchaseOrder)) {
                throw new LogicException(
                    "Purchase order not found"
                );
            }

            $paymentOrderProcessed = $this->paymentService->pay($purchaseOrder);
            if (empty($paymentOrderProcessed)) {
                throw new LogicException(
                    "Payment order not processed in API"
                );
            }

            $paymentOrderCreated = $this->paymentOrderService->buildRow(
                $purchaseOrderId,
                $paymentOrderProcessed
            )->store();
            if (empty($paymentOrderCreated)) {
                throw new LogicException(
                    "Payment order not save"
                );
            }

            broadcast(new PaymentReceived());
        } catch (PaymentNotReceivedException $e) {
            \Log::info([$e->getMessage(), $e->getTraceAsString()]);
            broadcast(new PaymentNotReceived(
                $e->getMessage(),
                [
                    "billingType" => $purchaseOrder['charge_medata']->billingType,
                    "bankSlipUrl" => $purchaseOrder['charge_medata']->bankSlipUrl
                ]
            ));
        }
    }

    public function queueConnection()
    {
        return 'redis';
    }

    public function queueName()
    {
        return 'payment_order';
    }
}
