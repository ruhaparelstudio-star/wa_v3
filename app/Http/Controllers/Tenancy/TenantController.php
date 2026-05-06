<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Modules\Tenancy\Services\TenantCreationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TenantController extends Controller
{
    public function __construct(
        private readonly TenantCreationService $tenantCreationService,
    ) {
    }

    public function create(Request $request): View
    {
        abort_if(! $request->user()?->isSuperadmin(), 403);

        return view('tenancy.create');
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $actor = $request->user();

        abort_if(! $actor?->isSuperadmin(), 403);

        $data = $request->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
            'tenant_slug' => ['nullable', 'string', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);

        $result = $this->tenantCreationService->createBySuperadmin($actor, $data);

        $payload = [
            'tenant' => [
                'id' => $result->tenant->id,
                'name' => $result->tenant->name,
                'slug' => $result->tenant->slug,
                'status' => $result->tenant->status,
            ],
            'tenant_admin' => [
                'id' => $result->tenantAdmin->id,
                'name' => $result->tenantAdmin->name,
                'email' => $result->tenantAdmin->email,
                'role' => $result->tenantAdmin->role,
            ],
            'activation_link' => $result->activationLink->url,
            'activation_expires_at' => $result->activationLink->activationToken->expires_at?->toISOString(),
        ];

        if ($request->expectsJson()) {
            return response()->json($payload, Response::HTTP_CREATED);
        }

        return redirect()
            ->route('superadmin.tenants.create')
            ->with('status', 'Tenant berhasil dibuat.')
            ->with('activation_link', $payload['activation_link']);
    }
}
