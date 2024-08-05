<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\tbl_menu;

class tbl_menuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tbl_menu_list = [
            [
                'permission_id' => 1,
                'parent_id' => null,
                'menuname' => 'User Management',
                'description' => 'test',
                'menulink' => '#',
                'icon' => 'MdManageAccounts',
            ],
            [
                'permission_id' => 2,
                'parent_id' => 1,
                'menuname' => 'Role',
                'description' => 'test',
                'menulink' => '/dashboard/Roles',
                'icon' => null,
            ],
            [
                'permission_id' => 7,
                'parent_id' => 1,
                'menuname' => 'Users',
                'description' => 'test',
                'menulink' => '/dashboard/users',
                'icon' => null,
            ],
            [
                'permission_id' => 13,
                'parent_id' => null,
                'menuname' => 'Config',
                'description' => 'test',
                'menulink' => '#',
                'icon' => 'GrDocumentConfig',
            ],
            [
                'permission_id' => 14,
                'parent_id' => 4,
                'menuname' => 'Organization',
                'description' => 'test',
                'menulink' => '/dashboard/organization',
                'icon' => null,
            ],
            [
                'permission_id' => 18,
                'parent_id' => 4,
                'menuname' => 'Workflow',
                'description' => 'test',
                'menulink' => '/dashboard/workflow',
                'icon' => null,
            ],
            [
                'permission_id' => 26,
                'parent_id' => null,
                'menuname' => 'Procurement Management',
                'description' => 'test',
                'menulink' => '#',
                'icon' => 'VscServerProcess',
            ],
            [
                'permission_id' => 27,
                'parent_id' => 7,
                'menuname' => 'Asset Requisitions',
                'description' => 'test',
                'menulink' => '/dashboard/asset_requisitions',
                'icon' => null,
            ],
            [
                'permission_id' => 31,
                'parent_id' => 7,
                'menuname' => 'Procurement Initiate',
                'description' => 'test',
                'menulink' => '/dashboard/procurement_initiate',
                'icon' => null,
            ],
            [
                'permission_id' => 36,
                'parent_id' => 7,
                'menuname' => 'Procurement Staff',
                'description' => 'test',
                'menulink' => '/dashboard/staff',
                'icon' => null,
            ],
            [
                'permission_id' => 40,
                'parent_id' => 7,
                'menuname' => 'Supplier Register',
                'description' => 'test',
                'menulink' => '/dashboard/supplier',
                'icon' => null,
            ],
            [
                'permission_id' => 44,
                'parent_id' => 7,
                'menuname' => 'Supplier Quotation',
                'description' => 'test',
                'menulink' => '/dashboard/supplier_quotation',
                'icon' => null,
            ],
        ];

        // Seed multiple permission
        foreach ($tbl_menu_list as $TBL_menu_list) {
            tbl_menu::create($TBL_menu_list);
        }
    }
}
