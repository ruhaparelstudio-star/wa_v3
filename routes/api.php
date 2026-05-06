<?php

use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthController::class, 'base'])->name('health.base');
Route::get('/health/db', [HealthController::class, 'database'])->name('health.database');
Route::get('/health/redis', [HealthController::class, 'redis'])->name('health.redis');
