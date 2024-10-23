<?php

namespace App\Services\Integrations\Asaas\Interfaces;

interface HttpRequestInterface
{
    public function request($method, $url, $data = [], $headers = []);
}
