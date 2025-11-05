<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $nonAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->nonAdmin = User::factory()->create(['role' => 'no-admin']);
    }

    public function test_non_admin_cannot_access_admin_user_index(): void
    {
        
        
         

        $this->actingAs($this->nonAdmin)
            ->get('/admin/users')
            ->assertRedirect('/dashboard');
    }

    public function test_admin_can_access_admin_user_index(): void
    {
        $this->withoutMiddleware(); 

        $this->actingAs($this->admin)
            ->get('/admin/users')
            ->assertSuccessful();
    }

    public function test_admin_can_create_user_with_upload(): void
    {
        $this->withoutMiddleware(); 
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($this->admin)->post('/admin/users', [
            'name' => 'New User',
            'full_name' => 'New User Full Name',
            'email' => 'newuser@app.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
            'avatar_upload' => $file,
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@app.com',
            'role' => 'admin',
            'full_name' => 'New User Full Name',
        ]);
        $user = User::where('email', 'newuser@app.com')->first();
        Storage::disk('public')->assertExists($user->avatar_image); 
    }

    public function test_admin_can_update_user_role(): void
    {
        $this->withoutMiddleware(); 
        $userToUpdate = User::factory()->create(['role' => 'no-admin']);

        $response = $this->actingAs($this->admin)->put("/admin/users/{$userToUpdate->id}", [
            'role' => 'admin',
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $this->withoutMiddleware(); 
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/users/{$userToDelete->id}");

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    public function test_user_creation_requires_full_name_and_role(): void
    {
        $this->withoutMiddleware(); 
        $response = $this->actingAs($this->admin)->post('/admin/users', [
            'name' => 'Invalid',
            'email' => 'invalid@app.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'full_name' => '', 
            'role' => 'invalid-role', 
        ]);

        $response->assertSessionHasErrors(['full_name', 'role']);
    }
}