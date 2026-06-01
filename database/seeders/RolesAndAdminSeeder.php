<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin     = Role::firstOrCreate(['name' => 'admin']);
        $developer = Role::firstOrCreate(['name' => 'developer']);

        $user = User::firstOrCreate(
            ['email' => 'admin@taskboard.test'],
            [
                'name'         => 'Administrador',
                'password'     => Hash::make('password'),
                'avatar_color' => '#6366f1',
            ]
        );
        $user->assignRole('admin');
    }
}
