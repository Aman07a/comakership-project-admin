<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Teddy',
            'email' => 'teddy@gmail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => Hash::make('password'),
            'is_admin' => 0,
            'is_deleted' => 0,
            'remember_token' => '',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
