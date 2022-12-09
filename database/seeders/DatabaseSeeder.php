<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $administratorRole = \App\Models\Role::factory()->create([
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);

        $userRole = \App\Models\Role::factory()->create([
            'name' => 'User',
            'slug' => 'user',
        ]);

        if (Config::get('app.env') == 'local') {
            $testAdministrator = \App\Models\User::factory()->create([
                'name' => 'Test Administrator',
                'email' => 'admin@example.com',
            ]);

            \App\Models\RoleUser::factory()->create([
                'user_id' => $testAdministrator->id,
                'role_id' => $administratorRole->id,
            ]);

            $testUser = \App\Models\User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

            \App\Models\RoleUser::factory()->create([
                'user_id' => $testUser->id,
                'role_id' => $userRole->id,
            ]);
        }
    }
}
