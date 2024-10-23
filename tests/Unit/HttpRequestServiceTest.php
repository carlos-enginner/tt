<?php

namespace Tests\Unit;

use App\Exceptions\PaymentGenericErrorException;
use App\Services\Integrations\Asaas\HttpRequestService;
use PHPUnit\Framework\Attributes\CoversClass;
// use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function Termwind\parse;

#[CoversClass(HttpRequestService::class)]
class HttpRequestServiceTest extends TestCase
{

    public function setUp():void
    {
        putenv("ASAAS_API_TOKEN=fake-token");
        putenv("ASAAS_API_URL=fake-url");
        parent::setUp();
    }
    
    public function test_construct_http_request_service_with_valid_token()
    {
        $validToken = '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwOTIxMzg6OiRhYWNoXzY1MDBiMWJmLTY3YzMtNDVlZS1hNzI0LTFjOWI1ODcwMDc4MQ==';
        putenv("ASAAS_API_TOKEN=$validToken");

        $service = new HttpRequestService();

        $this->assertInstanceOf(HttpRequestService::class, $service);
        $this->assertArrayHasKey('access_token', $service->getDefaultHeaders());
        $this->assertEquals($validToken, $service->getDefaultHeaders()['access_token']);
    }

    public function test_throw_exception_when_token_is_missing()
    {
        putenv("ASAAS_API_TOKEN=");

        $this->expectException(PaymentGenericErrorException::class);
        $this->expectExceptionMessage("API Token da Asaas não foi inserido");

        $bla = new HttpRequestService();

        var_dump($bla);
    }

    public function test_http_request_throws_exception_when_base_path_is_empty()
    {
        putenv("ASAAS_API_URL=");

        $this->expectException(PaymentGenericErrorException::class);
        $this->expectExceptionMessage("Endpoint da API Asaas não foi configurado");

        $service = new HttpRequestService();

        $service->request('get', '/fake-point');
    }

    public function test_returns_data_when_response_successful_and_no_errors()
    {
        $mockResponse = $this->createMock(\Illuminate\Http\Client\Response::class);
        $mockResponse->method('json')->willReturn(['key' => 'value']);
        $mockResponse->method('successful')->willReturn(true);

        $httpRequestService = new HttpRequestService();
        $result = $httpRequestService->handleResponse($mockResponse);

        $this->assertEquals(['key' => 'value'], $result);
    }

    public function test_throws_exception_when_response_unsuccessful_and_errors_empty()
    {
        $this->expectException(PaymentGenericErrorException::class);
        $this->expectExceptionMessage('Serviço indisponível.');

        $mockResponse = $this->createMock(\Illuminate\Http\Client\Response::class);
        $mockResponse->method('json')->willReturn([]);
        $mockResponse->method('successful')->willReturn(false);

        $httpRequestService = new HttpRequestService();
        $httpRequestService->handleResponse($mockResponse);
    }

    public function test_throws_exception_when_detects_presence_of_errors_key()
    {
        $mockResponse = $this->createMock(\Illuminate\Http\Client\Response::class);
        $mockResponse->method('json')->willReturn(['errors' => ['Error message']]);
        $mockResponse->method('successful')->willReturn(false);

        $httpRequestService = new \App\Services\Integrations\Asaas\HttpRequestService();

        $this->expectException(\App\Exceptions\PaymentGenericErrorException::class);
        $httpRequestService->handleResponse($mockResponse);
    }
}
