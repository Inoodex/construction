<?php

namespace Database\Seeders;

use HasinHayder\Tyro\Models\Privilege;
use HasinHayder\Tyro\Models\Role;
use Illuminate\Database\Seeder;

class ClientRoleSeeder extends Seeder
{
    public function run(): void
    {
        $privileges = [
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view'],
            ['name' => 'View Projects', 'slug' => 'projects.view'],
            ['name' => 'View Invoices', 'slug' => 'invoices.view'],
        ];

        foreach ($privileges as $p) {
            Privilege::updateOrCreate(
                ['slug' => $p['slug']],
                ['name' => $p['name']]
            );
        }

        $role = Role::updateOrCreate(
            ['slug' => 'client'],
            ['name' => 'Client', 'is_active' => true]
        );

        $privilegeModels = Privilege::whereIn('slug', array_column($privileges, 'slug'))->get();
        $role->privileges()->syncWithoutDetaching($privilegeModels->pluck('id')->toArray());
    }
}
