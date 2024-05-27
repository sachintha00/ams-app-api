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

        for ($i = 0; $i < 100; $i++) {
            User::create([
                'user_name' => $faker->unique()->userName,
                'email' => $faker->unique()->safeEmail,
                'name' => $faker->name,
                'contact_no' => $faker->phoneNumber,
                'profie_image' => $faker->imageUrl(),
                'contact_person' => $faker->name,
                'website' => $faker->url,
                'address' => $faker->address,
                'password' => Hash::make('password'),
                'employee_code' => $faker->randomNumber(5),
                'security_question' => $faker->sentence,
                'security_answer' => $faker->sentence,
                'activation_code' => $faker->uuid,
                'is_user_blocked' => false,
                'is_trial_account' => true,
                'first_login' => $faker->dateTimeThisYear(),
                'user_description' => $faker->paragraph,
                'status' => true,
                'created_user' => $faker->name,
                'tenant_db_name' => $faker->domainWord,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}