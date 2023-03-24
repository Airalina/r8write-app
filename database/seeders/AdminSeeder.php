<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'dni' => '010203',
        ];

        $userDB = User::firstOrCreate(
            [
                'email' => $user['email']
            ],
            $user
        );
        $userDB->syncRoles([]);
        $userDB->assignRole(User::ROLES['admin']);
    }
}
