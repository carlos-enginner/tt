<?php

namespace App\Jobs\Purchase;

use App\Jobs\Payment\PaymentOrder;
use App\Notifications\PaymentNotReceived;
use App\Services\Integrations\Asaas\ChargeService;
use App\Services\Integrations\Asaas\CustomerService;
use App\Services\Integrations\Asaas\HttpRequestService;
use App\Services\Purchase\PurchaseOrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class PurchaseOrder implements ShouldQueue
{
    use Queueable;

    protected $purchaseData;
    protected $purchaseOrderService;
    protected $asaasChargeService;
    protected $asaasCustomerService;

    /**
     * Create a new job instance.
     */
    public function __construct($purchaseData)
    {
        $this->purchaseData = $purchaseData;
        $httpRequestService = new HttpRequestService();
        $this->purchaseOrderService = new PurchaseOrderService();
        $this->asaasCustomerService = new CustomerService($httpRequestService);
        $this->asaasChargeService = new ChargeService($httpRequestService);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            Log::info("[PurchaseOrder::handle");
            Log::info($this->purchaseData);

            // customer service
            $customer = $this->asaasCustomerService->save($this->purchaseData);
            Log::info(["customer" => $customer]);

            // charge service
            $customerId = $customer["id"];
            $charge = $this->asaasChargeService->generate($this->purchaseData, $customerId);
            Log::info(["charge" => $charge]);

            // purchase order
            $this->purchaseData["payment_metadata"] = json_encode($this->purchaseData["payment_metadata"] ?: []);
            $this->purchaseData['consumer_transaction_metadata'] = json_encode($customer);
            $this->purchaseData['charge_transaction_metadata'] = json_encode($charge);
            $purchaseOrder = $this->purchaseOrderService->store($this->purchaseData);

            // enfileira o pagamento
            $paymentOrder = [
                'purchase_orders_id' => $purchaseOrder->id
            ];
            PaymentOrder::dispatch($paymentOrder)->onQueue('payment_order');
        } catch (Throwable $e) {
            Log::info("PurchaseOrder.handle -> Falha ao processar a compra");
            Log::info([$e->getMessage(), $e->getTraceAsString()]);
            broadcast(new PaymentNotReceived(
                $e->getMessage()
            ));
        }
    }

    public function queueConnection()
    {
        return 'redis';
    }

    public function queueName()
    {
        return 'purchase_order';
    }
}
