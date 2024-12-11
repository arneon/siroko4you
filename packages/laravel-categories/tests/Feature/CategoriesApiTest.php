<?php

namespace Arneon\LaravelCategories\Tests\Feature;

use Arneon\LaravelCategories\Tests\TestCase;

class CategoriesApiTest extends TestCase
{

    public function test_attempt_to_create_root_category_with_bad_name_field()
    {
        $array = [
            'payload' => [
                ['name' => 123],
                ['name' => ''],
                ['name' => '1234'],
                ['name' => '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890']
            ],
            'errorMessage' => [
                'The name field must be a string.',
                'Category name is required',
                'Category name must be at least 5 characters',
                'Category name may not be greater than 100 characters',
            ],
        ];

        $index=0;
        foreach ($array['payload'] as $payload) {
            $payload['slug'] = 'test-slug';
            $payload['description'] = 'Test description';
            $payload['image'] = 'test-image';
            $payload['enabled'] = 1;
            $payload['parent_id'] = '';

            $response = $this->withToken("Bearer {$this->token}")
                ->postJson('/api/categories', $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_create_root_category_with_bad_slug_field()
    {
        $array = [
            'payload' => [
                ['slug' => ''],
                ['slug' => 123],
                ['slug' => '1234'],
            ],
            'errorMessage' => [
                'Category slug is required',
                'Category slug must be a string.',
                'Category slug must be at least 5 characters',
            ],
        ];

        $index=0;
        foreach ($array['payload'] as $payload) {
            $payload['name'] = 'test-name';
            $payload['description'] = 'Test description';
            $payload['image'] = 'test-image';
            $payload['enabled'] = 1;
            $payload['parent_id'] = '';

            $response = $this->withToken("Bearer {$this->token}")
                ->postJson('/api/categories', $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_create_root_category_with_bad_enabled_field()
    {
        $array = [
            'payload' => [
                ['enabled' => ''],
                ['enabled' => "X"],
                ['enabled' => 1234],
            ],
            'errorMessage' => [
                'Enabled field is required',
                'Enabled field must be integer.',
                'Enabled field values must be 0 => false or 1 => true',
            ],
        ];

        $index=0;
        foreach ($array['payload'] as $payload) {
            $payload['name'] = 'test-name';
            $payload['slug'] = 'test-slug';
            $payload['description'] = 'Test description';
            $payload['image'] = 'test-image';
            $payload['parent_id'] = '';

            $response = $this->withToken("Bearer {$this->token}")
                ->postJson('/api/categories', $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_create_child_category_with_parent_that_do_not_exist()
    {
            $payload['name'] = 'test-name';
            $payload['slug'] = 'test-slug';
            $payload['description'] = 'Test description';
            $payload['image'] = 'test-image';
            $payload['enabled'] = 1;
            $payload['parent_id'] = 100;

            $response = $this->withToken("Bearer {$this->token}")
                ->postJson('/api/categories', $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains('Category parent id does not exist', $errorResponse['errors']['message']);
    }

    public function test_attempt_to_create_root_category_ok()
    {
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['enabled'] = 1;

        $response = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $payload);

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertSame(1, $data['data']['id']);
        $this->assertSame($payload['name'], $data['data']['name']);
        $this->assertSame($payload['slug'], $data['data']['slug']);
    }

    public function test_attempt_to_create_root_and_child_category_ok()
    {
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['enabled'] = 1;

        $response = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $payload);

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertSame(1, $data['data']['id']);
        $this->assertSame($payload['name'], $data['data']['name']);
        $this->assertSame($payload['slug'], $data['data']['slug']);
    }

    public function test_delete_category_not_found()
    {
        $response = $this->withToken("Bearer {$this->token}")
            ->deleteJson('/api/categories/111');
        $errorResponse = json_decode($response->getContent(), true);
        $response->assertStatus(400);
        $this->assertSame('given category id param does not exist', $errorResponse['errors']['message']);
    }


    public function test_delete_category_ok()
    {
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['enabled'] = 1;

        $createResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $payload);
        $data = json_decode($createResponse->getContent(), true);
        $id = $data['data']['id'];

        $response = $this->withToken("Bearer {$this->token}")
            ->deleteJson('/api/categories/' . $id);
        $errorResponse = json_decode($response->getContent(), true);
        $response->assertStatus(200);
    }

    public function test_attempt_to_update_root_category_with_errors()
    {
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['enabled'] = 1;

        $createResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $payload);

        $createResponse->assertStatus(200);
        $data = json_decode($createResponse->getContent(), true);
        $id = $data['data']['id'];

        $array = [
            'payload' => [
                ['slug' => ''],
                ['slug' => 123],
                ['slug' => '1234'],
            ],
            'errorMessage' => [
                'Category slug is required',
                'Category slug must be a string.',
                'Category slug must be at least 5 characters',
            ],
        ];

        $index=0;
        foreach ($array['payload'] as $payload) {
            $payload['name'] = 'test-name';
            $payload['description'] = 'Test description';
            $payload['image'] = 'test-image';
            $payload['enabled'] = 1;
            $payload['parent_id'] = '';

            $response = $this->withToken("Bearer {$this->token}")
                ->putJson('/api/categories/'.$id, $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_update_root_category_ok()
    {
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['enabled'] = 1;

        $createResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $payload);

        $createResponse->assertStatus(200);
        $data = json_decode($createResponse->getContent(), true);
        $id = $data['data']['id'];

        $payloadUpdate['name'] = 'test-name-update';
        $payloadUpdate['slug'] = 'test-slug-update';
        $payloadUpdate['description'] = 'Test description-update';
        $payloadUpdate['image'] = 'test-image-update';
        $payloadUpdate['enabled'] = 0;

        $updateResponse = $this->withToken("Bearer {$this->token}")
                ->putJson('/api/categories/'.$id, $payloadUpdate);

        $updateResponse->assertStatus(200);
        $data = json_decode($updateResponse->getContent(), true);
        $this->stringContains($payloadUpdate['name'], $data['data']['name']);
    }

}
