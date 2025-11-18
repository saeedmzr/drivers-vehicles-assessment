<?php

namespace Database\Seeders;

use App\Filament\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Only create admin if not exists
        if (!Admin::where('email', 'admin@example.com')->exists()) {
            Admin::create([
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
        }

        // Create additional test data only in non-testing environments
        if (!app()->environment('testing')) {
            User::factory(10)->create();
        }
    }
}
