<?php

namespace App\Modules\Shared\Health;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Throwable;

class HealthService
{
    /**
     * @return array{status: string, service: string}
     */
    public function base(): array
    {
        return [
            'status' => 'ok',
            'service' => config('app.name'),
        ];
    }

    /**
     * @return array{status: string, connection: string, error?: string}
     */
    public function database(): array
    {
        try {
            DB::connection()->getPdo();

            return [
                'status' => 'ok',
                'connection' => config('database.default'),
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'error',
                'connection' => config('database.default'),
                'error' => $exception->getMessage(),
            ];
        }
    }

    /**
     * @return array{status: string, connection: string, error?: string}
     */
    public function redis(): array
    {
        try {
            Redis::connection()->ping();

            return [
                'status' => 'ok',
                'connection' => config('database.redis.client'),
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'error',
                'connection' => config('database.redis.client'),
                'error' => $exception->getMessage(),
            ];
        }
    }
}
