<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        User::create([
            'user_name' => 'sachintha',
            'email' => 'sachintha@gmail.com',
            'name' => 'Sachintha Madhawa',
            'contact_no' => $faker->phoneNumber,
            'profile_image' => substr($faker->imageUrl(), 0, 191),
            'contact_person' => substr($faker->name, 0, 191),
            'website' => substr($faker->url, 0, 191),
            'address' => substr($faker->address, 0, 191),
            'password' => Hash::make('password'),
            'employee_code' => $faker->randomNumber(5),
            'security_question' => substr($faker->sentence, 0, 191),
            'security_answer' => substr($faker->sentence, 0, 191),
            'activation_code' => $faker->uuid,
            'is_user_blocked' => false,
            'is_trial_account' => true,
            'first_login' => $faker->dateTimeThisYear(),
            'user_description' => substr($faker->paragraph, 0, 191),
            'status' => true,
            'created_user' => substr($faker->name, 0, 191),
            'tenant_db_name' => substr($faker->domainWord, 0, 191),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}