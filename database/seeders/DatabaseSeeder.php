<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BrokerSeeder;
use Database\Seeders\PropertySeeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Cache::flush();
        $this->call(UserSeeder::class);
        $this->call(BrokerSeeder::class);
    }
}
