<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionByRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        $admin = Role::findByName(User::ROLES['admin'], 'api');
        $admin->givePermissionTo(User::PERMISSIONS);

        $sellerPermissions = [
            User::PERMISSIONS['leads.index'],
            User::PERMISSIONS['leads.show'],
            User::PERMISSIONS['leads.store'],
            User::PERMISSIONS['leads.update'],
            User::PERMISSIONS['sellers.index'],
            User::PERMISSIONS['sellers.show'],
            User::PERMISSIONS['sellers.store'],
            User::PERMISSIONS['sellers.update'],
            User::PERMISSIONS['quotes.index'],
            User::PERMISSIONS['quotes.show'],
            User::PERMISSIONS['quotes.store'],
            User::PERMISSIONS['quotes.update'],
            User::PERMISSIONS['quotes.delete'],
            User::PERMISSIONS['services.index']
        ];
        $seller = Role::findByName(User::ROLES['seller'], 'api');
        $seller->givePermissionTo($sellerPermissions);

        $leadPermissions = [
            User::PERMISSIONS['quotes.index'],
            User::PERMISSIONS['quotes.show'],
            User::PERMISSIONS['services.index']
        ];
        
        $lead = Role::findByName(User::ROLES['lead'], 'api');
        $lead->givePermissionTo(
            $leadPermissions
        );
    }
}
