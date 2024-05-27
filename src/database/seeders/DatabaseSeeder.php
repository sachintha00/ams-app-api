<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RequestTypesSeeder::class);
        $this->call(WorkflowBehaviorTypesSeeder::class);
        $this->call(WorkflowTypesSeeder::class);
    }
}
