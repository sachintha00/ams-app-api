<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\assest_requisition_priority_type;

class assest_requisition_priority_typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priority_type = [
            [
                'name' => "Normal",
                'description' => "test",
            ],
            [
                'name' => "Moderate",
                'description' => "test",
            ],
            [
                'name' => "High",
                'description' => "test",
            ],
            [
                'name' => "Highest",
                'description' => "test",
            ],
        ];

        // Seed multiple period_type
        foreach ($priority_type as $Priority_type) {
            assest_requisition_priority_type::create($Priority_type);
        }
    }
}
