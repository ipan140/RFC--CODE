<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

class LoginController extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $now = Carbon::now();

        $user = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'status' => 1,
                'profile_picture' => null,
                'api_token' => Str::random(60),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/admin/dashboard'); // Ganti jika login redirect ke dashboard/admin
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_wrong_password()
    {
        $now = Carbon::now();

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'status' => 1,
                'profile_picture' => null,
                'api_token' => Str::random(60),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'salahpassword',
        ]);

        $response->assertSessionHasErrors(); // login gagal
        $this->assertGuest();
    }

    /** @test */
    public function login_requires_email_and_password()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }
}
