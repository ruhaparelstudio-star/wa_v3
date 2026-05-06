<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('SUPERADMIN_EMAIL', 'superadmin@wa-v3.local');
        $password = (string) env('SUPERADMIN_PASSWORD', 'ChangeMe!2026');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('SUPERADMIN_EMAIL must be a valid email address.');
        }

        if (Str::length($password) < 12) {
            throw new RuntimeException('SUPERADMIN_PASSWORD must be at least 12 characters.');
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => (string) env('SUPERADMIN_NAME', 'Super Admin'),
                'password' => Hash::make($password),
                'role' => User::ROLE_SUPERADMIN,
                'email_verified_at' => now(),
            ],
        );
    }
}
