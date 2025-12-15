<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $userRole  = Role::where('name', 'User')->first();

        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'contact_number' => '9999999999',
            'password' => Hash::make('password'),
        ]);

        if ($adminRole) {
            $admin->assignRole($adminRole);
        }

        User::factory(10)->create()->each(function ($user) use ($userRole) {
            if ($userRole) {
                $user->assignRole($userRole);
            }
        });
    }
}
