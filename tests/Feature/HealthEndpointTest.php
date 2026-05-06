<?php

namespace Tests\Feature;

use App\Modules\Shared\Health\HealthService;
use Tests\TestCase;

class HealthEndpointTest extends TestCase
{
    public function test_base_health_endpoint_returns_ok(): void
    {
        $this->getJson('/health')
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
                'service' => 'WA V3',
            ]);
    }

    public function test_database_health_endpoint_returns_ok_when_service_is_ok(): void
    {
        $this->mock(HealthService::class)
            ->shouldReceive('database')
            ->once()
            ->andReturn([
                'status' => 'ok',
                'connection' => 'mysql',
            ]);

        $this->getJson('/health/db')
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
                'connection' => 'mysql',
            ]);
    }

    public function test_redis_health_endpoint_returns_unavailable_when_service_fails(): void
    {
        $this->mock(HealthService::class)
            ->shouldReceive('redis')
            ->once()
            ->andReturn([
                'status' => 'error',
                'connection' => 'predis',
                'error' => 'Connection refused',
            ]);

        $this->getJson('/health/redis')
            ->assertStatus(503)
            ->assertJson([
                'status' => 'error',
                'connection' => 'predis',
            ]);
    }
}
