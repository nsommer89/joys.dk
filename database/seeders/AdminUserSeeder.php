<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gender;
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
                'name' => 'Niko',
                'username' => 'niko',
                'password' => bcrypt('password'),
                'gender_id' => $mandGender?->id,
            ]
        );

        if ($systemAdminRole && !$user->hasRole($systemAdminRole)) {
            $user->assignRole($systemAdminRole);
        }
    }
}
