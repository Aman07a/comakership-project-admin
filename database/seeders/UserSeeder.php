<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Ihab Kaluf',
            'email' => 'i.kaluf@eazlee.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'phone_number' => null,
            'alternate_phone_number' => null,
            'password' => bcrypt('123456'),
            'is_admin' => 1,
            'remember_token' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Aman Ahmed',
            'email' => 'aman@gmail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'phone_number' => null,
            'alternate_phone_number' => null,
            'password' => bcrypt('password'),
            'is_admin' => 1,
            'remember_token' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'phone_number' => null,
            'alternate_phone_number' => null,
            'password' => bcrypt('password'),
            'is_admin' => 0,
            'remember_token' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Tester',
            'email' => 'tester@gmail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'phone_number' => null,
            'alternate_phone_number' => null,
            'password' => bcrypt('password'),
            'is_admin' => 0,
            'remember_token' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Overwriter',
            'email' => 'overwriter@gmail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'phone_number' => null,
            'alternate_phone_number' => null,
            'password' => bcrypt('password'),
            'is_admin' => 0,
            'remember_token' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Validator',
            'email' => 'validator@gmail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'phone_number' => null,
            'alternate_phone_number' => null,
            'password' => bcrypt('password'),
            'is_admin' => 0,
            'remember_token' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Scheduler',
            'email' => 'scheduler@gmail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'phone_number' => null,
            'alternate_phone_number' => null,
            'password' => bcrypt('password'),
            'is_admin' => 0,
            'remember_token' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
