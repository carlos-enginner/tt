<?php

namespace Tests\Unit;

use App\Exceptions\PaymentNotReceivedException;
use App\Services\Integrations\Asaas\ChargeService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ChargeService::class)]
class ChargeServiceTest extends TestCase
{
    public function test_generate_payment_success()
    {
        // implementar
        $this->assertTrue(true);
    }
}
