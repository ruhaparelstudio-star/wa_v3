<?php

namespace Tests\Unit;

use App\Modules\Shared\Health\HealthService;
use Tests\TestCase;

class HealthServiceTest extends TestCase
{
    public function test_base_health_payload_is_ok(): void
    {
        $payload = app(HealthService::class)->base();

        $this->assertSame('ok', $payload['status']);
        $this->assertSame('WA V3', $payload['service']);
    }
}
