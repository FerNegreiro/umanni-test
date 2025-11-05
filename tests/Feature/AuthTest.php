<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create([
            'email' => 'testadmin@app.com',
            'password' => \Hash::make('password'),
            'role' => 'admin',
            'name' => 'Admin User',
            'full_name' => 'Admin User',
            'avatar_image' => 'avatar.jpg',
        ]);
    }

    public function test_guests_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'full_name' => 'Test User',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'no-admin',
        ]);
    }

    public function test_admin_is_redirected_to_admin_dashboard_on_login(): void
    {
        $response = $this->post('/login', [
            'email' => 'testadmin@app.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_non_admin_is_redirected_to_profile_on_login(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@app.com',
            'password' => \Hash::make('password'),
            'role' => 'no-admin',
            'name' => 'Test User',
            'full_name' => 'Test User',
        ]);

        $response = $this->post('/login', [
            'email' => 'testuser@app.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/profile');
    }
}
