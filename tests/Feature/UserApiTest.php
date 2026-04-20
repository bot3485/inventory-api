<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    /**
     * Use RefreshDatabase trait to reset the database
     * using migrations before each test run.
     */
    use RefreshDatabase;

    /** @test */
    public function test_admin_can_access_users_list()
    {
        // 1. Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Make an authenticated request (via Sanctum)
        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/users');

        // 3. Assert the response status is 200 OK and verify JSON structure
        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    /** @test */
    public function test_regular_user_cannot_update_others()
    {
        // 1. Create two regular users
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);

        // 2. Attempt to update user2's data as user1
        $response = $this->actingAs($user1, 'sanctum')
            ->putJson("/api/users/{$user2->id}", [
                'name' => 'Hacker Name'
            ]);

        // 3. Expect 403 Forbidden (as enforced by UserPolicy)
        $response->assertStatus(403);
    }
}
