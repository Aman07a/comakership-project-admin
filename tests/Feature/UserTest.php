<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_form()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    // public function test_user_dupplication()
    // {
    //     $user1  = User::make([
    //         'name' => 'Janick',
    //         'email' => 'janick@gmail.com',
    //         'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //         'password' => bcrypt('password'),
    //         'is_admin' => 1,
    //         'is_deleted' => 0,
    //         'remember_token' => '',
    //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //         'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //     ]);

    //     $user2  = User::make([
    //         'name' => 'Teddy',
    //         'email' => 'teddy@gmail.com',
    //         'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //         'password' => bcrypt('password'),
    //         'is_admin' => 1,
    //         'is_deleted' => 0,
    //         'remember_token' => '',
    //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //         'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //     ]);

    //     $this->assertTrue($user1->name != $user2->name);
    // }

    // public function test_delete_user()
    // {
    //     $user = User::factory()->count(1)->make();

    //     $user = User::first();
    //     if ($user) {
    //         $user->delete();
    //     }

    //     $this->assertTrue(true);
    // }

    // public function test_it_stores_new_users()
    // {
    //     $response = $this->post(
    //         'register',
    //         [
    //             'name' => 'Teddy',
    //             'email' => 'teddy@gmail.com',
    //             'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //             'password' => bcrypt('password'),
    //             'is_admin' => 1,
    //             'is_deleted' => 0,
    //             'remember_token' => '',
    //             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //             'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //         ]
    //     );

    //     $response->assertRedirect('/');
    // }

    // public function test_database()
    // {
    //     $this->assertDatabaseMissing('users', [
    //         'name' => 'Aman',
    //     ]);
    // }

    public function test_refresh_migration_and_seed()
    {
        /**
         * Seed all seeders in the Seeders folder
         * php artisan db:seed
         */
        // $this->seed();
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');

        $this->assertTrue(true);
    }
}
