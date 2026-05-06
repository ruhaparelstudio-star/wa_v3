<?php

use App\Http\Controllers\HealthController;
use App\Modules\WhatsApp\Http\Controllers\InboundGatewayController;
use App\Modules\WhatsApp\Http\Controllers\WAAccountStatusController;
use App\Modules\WhatsApp\Http\Middleware\VerifyGatewayRequest;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthController::class, 'base'])->name('health.base');
Route::get('/health/db', [HealthController::class, 'database'])->name('health.database');
Route::get('/health/redis', [HealthController::class, 'redis'])->name('health.redis');

Route::middleware(VerifyGatewayRequest::class)
    ->prefix('internal/whatsapp')
    ->name('internal.whatsapp.')
    ->group(function (): void {
        Route::post('/inbound', InboundGatewayController::class)->name('inbound');
        Route::post('/accounts/{waAccount}/status', WAAccountStatusController::class)->name('accounts.status');
    });
