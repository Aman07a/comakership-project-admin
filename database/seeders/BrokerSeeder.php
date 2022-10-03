<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrokerSeeder extends Seeder
{
    /**
     * Remove this seeder when using in development or main
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('brokers')->insert([
        //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'name' => 'Brickstwente',
        //     'api_key' => env('API_KEY'),
        //     // 'user_id' => 3, // User
        //     'user_id' => 2, // Admin
        // ]);

        // DB::table('brokers')->insert([
        //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'name' => 'Test',
        //     'api_key' => env('DEV_API_KEY'),
        //     'user_id' => 4, // Tester
        //     // 'user_id' => 2, // Admin
        // ]);

        // DB::table('brokers')->insert([
        //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'name' => 'Validate',
        //     'api_key' => env('DEV_API_KEY'),
        //     'image' => null,
        //     'user_id' => 6,
        // ]);

        DB::table('brokers')->insert([
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'name' => 'Schedule',
            'api_key' => env('API_KEY'),
            'image' => null,
            'user_id' => 7,
        ]);
    }
}
