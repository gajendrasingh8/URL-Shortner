<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;


class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'SuperAdmin')->first();
        if (!$role) {
            return;
        }

        User::firstOrCreate(
            ['email' => 'gajendraadmin@gmail.com'],
            [
                'name' => 'Gajendra Super Admin',
                'password' => Hash::make('Superadmin@1'),
                'role_id' => $role->id,
                'company_id' => null
            ]
        );

    }
}
