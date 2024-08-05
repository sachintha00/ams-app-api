<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestTypesSeeder extends Seeder
{
    public function run(): void
    {
        $requestTypes = [
            ['request_type' => 'Asset Requisition'],
            ['request_type' => 'Supplier Registration'],
            ['request_type' => 'Procurement Request'],
        ];

        DB::table('workflow_request_types')->insert($requestTypes);
    }
}