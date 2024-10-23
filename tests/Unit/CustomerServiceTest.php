<?php

namespace Tests\Unit;

use App\Services\Integrations\Asaas\CustomerService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CustomerService::class)]
class CustomerServiceTest extends TestCase
{
    public function test_save_customer_success()
    {
        // implementar
        $this->assertTrue(true);
    }
}
