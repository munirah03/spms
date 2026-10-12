<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the first supervisor login only. Staff accounts are created by a
     * supervisor in the UI (public registration is disabled).
     *
     * Set SUPERVISOR_EMAIL / SUPERVISOR_PASSWORD in .env, otherwise a random
     * password is generated and printed once.
     */
    public function run(): void
    {
        $password = env('SUPERVISOR_PASSWORD') ?: Str::password(16);

        User::updateOrCreate(
            ['email' => env('SUPERVISOR_EMAIL', 'supervisor@snapmomen.test')],
            [
                'name' => 'Snap Momen Supervisor',
                'role' => 'supervisor',
                'password' => Hash::make($password),
                'must_change_password' => true,
            ]
        );

        if (! env('SUPERVISOR_PASSWORD')) {
            $this->command?->warn("Supervisor password (shown once): {$password}");
        }
    }
}
