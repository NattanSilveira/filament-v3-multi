<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //create a super admin
        $superAdmin = User::factory()->create([
            'name' => 'Nattan Sousa',
            'email' => 'nattan_lion@msn.com',
            'password' => bcrypt('password'),
        ]);

        //add roles to super admin
        $superAdmin->assignRole('super_admin');

        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user->assignRole('app_user');
    }
}
