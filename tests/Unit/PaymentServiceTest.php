<?php

namespace Tests\Unit;

use App\Exceptions\PaymentNotReceivedException;
use App\Services\Integrations\Asaas\HttpRequestService;
use App\Services\Integrations\Asaas\Methods\BankSlipPayment;
use App\Services\Integrations\Asaas\PaymentService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(PaymentService::class)]
class PaymentServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        putenv('ASAAS_API_TOKEN=fake_url');
    }

    public function test_retrieve_purchase_order_with_valid_id()
    {
        $purchaseId = '10';
        $expectedResponse = [
            'payment_metadata' => (object) ['key' => 'value'],
            'charge_medata' => (object) ['transaction' => 'data']
        ];

        $httpRequestServiceMock = $this->createMock(HttpRequestService::class);
        $httpRequestServiceMock->method('request')
            ->with('get', "orders/{$purchaseId}")
            ->willReturn([
                'payment_metadata' => json_encode(['key' => 'value']),
                'charge_transaction_metadata' => json_encode(['transaction' => 'data'])
            ]);

        $paymentService = new PaymentService($httpRequestServiceMock);
        $result = $paymentService->getPurchaseOrder($purchaseId);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_payment_generic_error_exception_for_unrecognized_billing_type()
    {
        $this->expectException(PaymentNotReceivedException::class);
        $this->expectExceptionMessage("Não foi possível processar o pagamento <(0_0)>.");

        $mockHttpRequestService = $this->createMock(HttpRequestService::class);
        $mockHttpRequestService->method('request')
            ->willReturn([
                'payment_metadata' => json_encode(['some' => 'metadata']),
                'charge_transaction_metadata' => json_encode(['billingType' => 'unknown_type'])
            ]);

        $paymentService = new PaymentService($mockHttpRequestService);
        $chargeOrder = $this->createMock(stdClass::class);
        $chargeOrder->billingType = 'unknown_type';
        $purchaseOrder = [
            'charge_medata' => $chargeOrder,
            'payment_metadata' => []
        ];

        $paymentService->pay($purchaseOrder);
    }

    public function test_successful_payment_with_correct_method()
    {
        $mockPaymentMethod = $this->createMock(BankSlipPayment::class);
        $mockPaymentMethod->expects($this->once())
            ->method('pay')
            ->with($this->anything(), $this->anything())
            ->willReturn(true);

        $mockHttpRequestService = $this->createMock(HttpRequestService::class);
        $mockHttpRequestService->method('request')
            ->willReturn([
                'payment_metadata' => json_encode(['some' => 'metadata']),
                'charge_transaction_metadata' => json_encode(['billingType' => 'BANK_SLIP'])
            ]);


        $paymentService = new PaymentService($mockHttpRequestService);
        $paymentService->paymentMethods = [
            'BANK_SLIP' => $mockPaymentMethod
        ];

        $purchaseOrder = [
            'charge_medata' => (object)['billingType' => 'BANK_SLIP'],
            'payment_metadata' => []
        ];

        $result = $paymentService->pay($purchaseOrder);

        $this->assertTrue($result);
    }

    public function test_handles_missing_payment_metadata_gracefully()
    {
        $mockPaymentMethod = $this->createMock(BankSlipPayment::class);
        $mockPaymentMethod->expects($this->once())
            ->method('pay');

        $mockHttpRequestService = $this->createMock(HttpRequestService::class);
        $mockHttpRequestService->method('request')
            ->willReturn([
                'payment_metadata' => null,
                'charge_transaction_metadata' => json_encode(['billingType' => 'BANK_SLIP'])
            ]);

        $paymentService = new PaymentService($mockHttpRequestService);
        $paymentService->paymentMethods = [
            'BANK_SLIP' => $mockPaymentMethod
        ];

        $purchaseOrder = [
            'charge_medata' => (object)['billingType' => 'BANK_SLIP'],
            'payment_metadata' => null
        ];

        $result = $paymentService->pay($purchaseOrder);
        $this->assertNull($result);
    }
}
