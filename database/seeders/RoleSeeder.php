<?php

// File: database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        $roles = [
            'admin' => 'Admin',
            'sales' => 'Sales',
            'support' => 'Support',
            'marketing' => 'Marketing',
        ];

        foreach ($roles as $role => $name) {
            Role::create([
                'name' => $name,
                'guard_name' => 'web',
            ]);
        }

        // Assign permissions to roles
        $permissions = [
            'admin' => [
                'create-users',
                'edit-users',
                'delete-users',
                'view-users',
                'create-roles',
                'edit-roles',
                'delete-roles',
                'view-roles',
                'create-permissions',
                'edit-permissions',
                'delete-permissions',
                'view-permissions',
                'view-leads',
                'create-leads',
                'edit-leads',
                'delete-leads',
                'view-contacts',
                'create-contacts',
                'edit-contacts',
                'delete-contacts',
                'view-accounts',
                'create-accounts',
                'edit-accounts',
                'delete-accounts',
            ],
            'sales' => [
                'view-leads',
                'create-leads',
                'edit-leads',
                'delete-leads',
                'view-contacts',
                'create-contacts',
                'edit-contacts',
                'delete-contacts',
                'view-accounts',
                'create-accounts',
                'edit-accounts',
                'delete-accounts',
            ],
            'support' => [
                'view-contacts',
                'create-contacts',
                'edit-contacts',
                'delete-contacts',
                'view-accounts',
                'create-accounts',
                'edit-accounts',
                'delete-accounts',
            ],
            'marketing' => [
                'view-leads',
                'create-leads',
                'edit-leads',
                'delete-leads',
                'view-contacts',
                'create-contacts',
                'edit-contacts',
                'delete-contacts',
                'view-accounts',
                'create-accounts',
                'edit-accounts',
                'delete-accounts',
            ],
        ];

        foreach ($roles as $role => $name) {
            foreach ($permissions[$role] as $permission) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);

                $role->givePermissionTo($permission);
            }
        }
    }
}