<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test user registration.
     */
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    /**
     * Test user login.
     */
    public function test_user_can_login()
    {
        // Create a user
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);
        
        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);
    }

    /**
     * Test user details retrieval with authentication.
     */
    public function test_authenticated_user_can_get_user_details()
    {
        $user = User::factory()->create();

        // Act as the user
        $response = $this->actingAs($user, 'api')->getJson('/api/v1/user');

        $response->assertStatus(200)
            ->assertJson([
                'name' => $user->name,
                'email' => $user->email
            ]);
    }
}