<?php

namespace App\Http\Controllers;

use App\Modules\Shared\Health\HealthService;
use Illuminate\Http\JsonResponse;

class HealthController
{
    public function __construct(
        private readonly HealthService $healthService,
    ) {
    }

    public function base(): JsonResponse
    {
        return response()->json($this->healthService->base());
    }

    public function database(): JsonResponse
    {
        $result = $this->healthService->database();

        return response()->json($result, $result['status'] === 'ok' ? 200 : 503);
    }

    public function redis(): JsonResponse
    {
        $result = $this->healthService->redis();

        return response()->json($result, $result['status'] === 'ok' ? 200 : 503);
    }
}
