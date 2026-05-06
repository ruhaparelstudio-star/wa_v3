<?php

use App\Http\Controllers\Activation\TenantActivationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Tenancy\TenantController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/health')->name('home');

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::view('/dashboard', 'dashboard')
    ->middleware('auth')
    ->name('dashboard');

Route::get('/activate/{token}', [TenantActivationController::class, 'show'])->name('activation.show');
Route::post('/activate/{token}', [TenantActivationController::class, 'store'])->name('activation.store');

Route::get('/superadmin/tenants/create', [TenantController::class, 'create'])
    ->middleware('auth')
    ->name('superadmin.tenants.create');
Route::post('/superadmin/tenants', [TenantController::class, 'store'])
    ->middleware('auth')
    ->name('superadmin.tenants.store');
