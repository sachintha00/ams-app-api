<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = [
            [
                'name' => 'view dashboard',
                'description' => 'test',
            ],
            [
                'name' => 'User Management',
                'description' => 'test',
            ],
            [
                'name' => 'view role',
                'description' => 'test',
            ],
            [
                'name' => 'create role',
                'description' => 'test',
            ],
            [
                'name' => 'update role',
                'description' => 'test',
            ],
            [
                'name' => 'delete role',
                'description' => 'test',
            ],
            [
                'name' => 'view user',
                'description' => 'test',
            ],
            [
                'name' => 'create user',
                'description' => 'test',
            ],
            [
                'name' => 'update user',
                'description' => 'test',
            ],
            [
                'name' => 'delete user',
                'description' => 'test',
            ],
        ];

        // Seed multiple permission
        foreach ($permission as $Permission) {
            Permission::create($Permission);
        }
    }
}
