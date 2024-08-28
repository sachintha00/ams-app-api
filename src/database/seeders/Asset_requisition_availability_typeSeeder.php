<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asset_requisition_availability_types;

class Asset_requisition_availability_typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $availability_type = [
            [
                'name' => "Hire",
                'description' => "test",
            ],
            [
                'name' => "Rent",
                'description' => "test",
            ],
            [
                'name' => "Purchase",
                'description' => "test",
            ],
            [
                'name' => "Lease",
                'description' => "test",
            ],
        ];

        // Seed multiple period_type
        foreach ($availability_type as $Availability_type) {
            asset_requisition_availability_types::create($Availability_type);
        }
    }
}