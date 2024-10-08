<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\assest_requisition_period_type;

class assest_requisition_period_typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $period_type = [
            [
                'name' => "Long period/terms",
                'description' => "test",
            ],
            [
                'name' => "Short period/terms",
                'description' => "test",
            ],
            [
                'name' => "Temporary",
                'description' => "test",
            ],
            [
                'name' => "Permanent",
                'description' => "test",
            ],
        ];

        // Seed multiple period_type
        foreach ($period_type as $Period_type) {
            assest_requisition_period_type::create($Period_type);
        }
    }
}
