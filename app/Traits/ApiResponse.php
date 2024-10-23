<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success($data = null, $message = 'Requisição bem-sucedida', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'error' => null,
            'message' => $message,
        ], $statusCode);
    }

    protected function error($code, $message, $details = null, $statusCode = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'data' => null,
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
            'message' => 'Erro ao processar a requisição.',
        ], $statusCode);
    }
}
