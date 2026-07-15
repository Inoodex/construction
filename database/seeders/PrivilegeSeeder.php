<?php

namespace Database\Seeders;

use HasinHayder\Tyro\Models\Privilege;
use HasinHayder\Tyro\Models\Role;
use Illuminate\Database\Seeder;

class PrivilegeSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'finance' => [
                'name' => 'Finance',
                'privileges' => ['view', 'create', 'edit', 'delete', 'export'],
            ],
            'hr' => [
                'name' => 'HR & Payroll',
                'privileges' => ['view', 'create', 'edit', 'delete', 'export'],
            ],
            'procurement' => [
                'name' => 'Procurement',
                'privileges' => ['view', 'create', 'edit', 'delete', 'export'],
            ],
            'core' => [
                'name' => 'Core',
                'privileges' => ['view', 'create', 'edit', 'delete', 'export'],
            ],
            'quality' => [
                'name' => 'Quality Control',
                'privileges' => ['view', 'create', 'edit', 'delete', 'export'],
            ],
            'crm' => [
                'name' => 'CRM',
                'privileges' => ['view', 'create', 'edit', 'delete', 'export'],
            ],
            'reports' => [
                'name' => 'Reports',
                'privileges' => ['view', 'create', 'export'],
            ],
            'admin' => [
                'name' => 'Administration',
                'privileges' => ['view', 'manage'],
            ],
        ];

        $allPrivileges = [];

        foreach ($modules as $moduleSlug => $module) {
            foreach ($module['privileges'] as $action) {
                $slug = "{$moduleSlug}.{$action}";
                $privilege = Privilege::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => ucfirst($action) . ' ' . $module['name'],
                        'description' => "Allow {$action} operations on {$module['name']} module",
                    ]
                );
                $allPrivileges[$slug] = $privilege;
            }
        }

        $superAdmin = Role::where('slug', 'super-admin')->first();
        $admin = Role::where('slug', 'admin')->first();
        $accountant = Role::where('slug', 'accountant')->first();

        if ($superAdmin) {
            $superAdmin->privileges()->sync($allPrivileges);
        }

        if ($admin) {
            $adminPrivileges = collect($allPrivileges)->filter(fn ($p) => !str_starts_with($p->slug, 'admin.'))->pluck('id')->toArray();
            $admin->privileges()->sync($adminPrivileges);
        }

        if ($accountant) {
            $financePrivileges = collect($allPrivileges)->filter(fn ($p) => str_starts_with($p->slug, 'finance.') || str_starts_with($p->slug, 'reports.'))->pluck('id')->toArray();
            $accountant->privileges()->sync($financePrivileges);
        }
    }
}
