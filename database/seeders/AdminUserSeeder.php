<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $systemAdminRole = Role::where('name', 'System Admin')->first();
        $mandGender = Gender::where('slug', 'mand')->first();

        $user = User::firstOrCreate(
            ['email' => 'nsj@yap.dk'],
            [
                'name' => 'Nikolaj',
                'username' => 'Webmaster',
                'password' => '$2y$12$xnmVMfkpA1mpZH6vFZmleeMQf.dqvhylQM5.HxxUnW0YZ7pEKLE7W',
                'gender_id' => $mandGender?->id,
            ]
        );

        if ($systemAdminRole && ! $user->hasRole($systemAdminRole)) {
            $user->assignRole($systemAdminRole);
        }
    }
}
