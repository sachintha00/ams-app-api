<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RequestTypesSeeder::class);
        $this->call(WorkflowBehaviorTypesSeeder::class);
        $this->call(WorkflowTypesSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PrefixTypesSeeder::class);
        $this->call(PrefixesSeeder::class);
        $this->call(AssetTypesSeeder::class);

        // $this->call(assest_requisition_availability_typeSeeder::class);
        // $this->call(assest_requisition_period_typeSeeder::class);
        // $this->call(assest_requisition_priority_typeSeeder::class);

        // $this->call(DrawerDataSeeder::class);

        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleHasPermissionsSeeder::class);
        $this->call(ModelHasRolesSeeder::class);
        $this->call(tbl_menuSeeder::class);
    }
}