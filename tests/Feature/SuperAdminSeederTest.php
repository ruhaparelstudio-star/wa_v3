<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\SuperAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperAdminSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_seeder_creates_login_ready_superadmin(): void
    {
        $this->seed(SuperAdminSeeder::class);

        $superadmin = User::query()
            ->where('email', 'superadmin@wa-v3.local')
            ->firstOrFail();

        $this->assertSame(User::ROLE_SUPERADMIN, $superadmin->role);
        $this->assertTrue(Hash::check('ChangeMe!2026', $superadmin->password));
        $this->assertNotNull($superadmin->email_verified_at);
    }

    public function test_superadmin_seeder_is_idempotent(): void
    {
        $this->seed(SuperAdminSeeder::class);
        $this->seed(SuperAdminSeeder::class);

        $this->assertSame(1, User::query()->where('role', User::ROLE_SUPERADMIN)->count());
    }
}
