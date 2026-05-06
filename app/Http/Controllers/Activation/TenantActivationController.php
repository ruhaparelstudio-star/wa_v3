<?php

namespace App\Http\Controllers\Activation;

use App\Http\Controllers\Controller;
use App\Modules\Activation\Services\ActivationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class TenantActivationController extends Controller
{
    public function __construct(
        private readonly ActivationService $activationService,
    ) {
    }

    public function show(string $token): View
    {
        abort_unless($this->activationService->validTokenExists($token), 404);

        return view('auth.activate', [
            'token' => $token,
        ]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $this->ensureIsNotRateLimited($request, $token);

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->activationService->activate(
            plainToken: $token,
            password: $data['password'],
            name: $data['name'] ?? null,
        );

        return redirect()
            ->route('login')
            ->with('status', 'Akun tenant berhasil diaktivasi. Silakan masuk.');
    }

    private function ensureIsNotRateLimited(Request $request, string $token): void
    {
        $key = 'activation:'.$token.'|'.$request->ip();

        if (! RateLimiter::tooManyAttempts($key, 5)) {
            RateLimiter::hit($key, 60);

            return;
        }

        throw ValidationException::withMessages([
            'token' => 'Terlalu banyak percobaan aktivasi. Coba lagi dalam '.RateLimiter::availableIn($key).' detik.',
        ]);
    }
}
