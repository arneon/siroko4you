<?php

namespace Arneon\LaravelUsers\Tests\Feature;

use Arneon\LaravelUsers\Tests\TestCase;
use App\Models\User;

class UserApiTest extends TestCase
{

    public function test_create_user()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => '12345678',
        ];
        $response = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/users', $payload);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);

        $secondUser = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/users', $payload);
        $secondUser->assertStatus(400);
        $secondUser->assertContent('{"errors":{"message":"Email is already in use"}}');

        $payload['email'] = 'test3@test.com';
        $payload['password'] = '123456';
        $thirdUser = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/users', $payload);
        $thirdUser->assertStatus(400);
        $thirdUser->assertContent('{"errors":{"message":"Password must be at least 8 characters"}}');
    }

    public function test_update_user()
    {
        $firstUser = User::factory()->create();

        $payload = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
        ];
        $response = $this->withToken("Bearer {$this->token}")
            ->putJson("/api/users/{$firstUser->id}", $payload);
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'email']
            ]);
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);

        $secondUser = User::factory()->create();
        $response = $this->withToken("Bearer {$this->token}")
            ->putJson("/api/users/{$secondUser->id}", $payload);
        $response->assertStatus(400);
        $response->assertContent('{"errors":{"message":"Email is already in use"}}');
    }

    public function test_get_all_users()
    {
        User::factory(7)->create();

        $response = $this->withToken("Bearer {$this->token}")
            ->getJson('/api/users' );
        $response->assertStatus(200);
        $response->assertJsonCount(8, 'data');
    }

    public function test_get_user_by_id()
    {
        $user = User::factory()->create();

        $response = $this->withToken("Bearer {$this->token}")
            ->getJson("/api/users/{$user->id}/id" );
        $response->assertStatus(200);

        $response2 = $this->withToken("Bearer {$this->token}")
            ->getJson("/api/users/{$user->id}/xxx" );
        $response2->assertStatus(400);
        $response2->assertContent('{"errors":{"message":"Field does not exist"}}');
    }

    public function test_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->withToken("Bearer {$this->token}")
            ->deleteJson("/api/users/{$user->id}");
        $response->assertStatus(200);
        $response->assertContent('{"data":{"message":"User deleted successfully."}}');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        $response2 = $this->withToken("Bearer {$this->token}")
            ->deleteJson("/api/users/{$user->id}");
        $response2->assertStatus(400);
        $response2->assertContent('{"errors":{"message":"given id param does not exist"}}');
    }
}
