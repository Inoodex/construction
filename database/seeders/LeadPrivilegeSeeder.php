<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use HasinHayder\Tyro\Models\Privilege;

class LeadPrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $privileges = [
            [
                'name' => 'Create Lead',
                'slug' => 'create-lead',
            ],
            [
                'name' => 'View Leads',
                'slug' => 'view-leads',
            ],
            [
                'name' => 'Update Lead',
                'slug' => 'update-lead',
            ],
            [
                'name' => 'Delete Lead',
                'slug' => 'delete-lead',
            ],
        ];

        foreach ($privileges as $privilege) {
            Privilege::updateOrCreate(
                ['slug' => $privilege['slug']],
                ['name' => $privilege['name']]
            );
        }
    }
}
