<?php

namespace Arneon\LaravelProducts\Tests\Feature;

use Arneon\LaravelProducts\Tests\TestCase;

class ProductsApiTest extends TestCase
{

    public function test_attempt_to_create_parent_product_with_bad_sku_field()
    {
        $array = [
            'payload' => [
                ['name' => 123],
                ['name' => ''],
                ['name' => '1234'],
                ['name' => '1234567890123456789012']
            ],
            'errorMessage' => [
                'Product SKU must be string.',
                'Product SKU is required',
                'Product SKU must be at least 3 characters',
                'Product SKU may not be greater than 20 characters',
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
                ->postJson('/api/products', $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_create_parent_product_with_bad_name_field()
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
                ->postJson('/api/products', $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_create_parent_product_with_bad_slug_field()
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
                ->postJson('/api/products', $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_create_parent_product_with_bad_enabled_field()
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
                ->postJson('/api/products', $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_create_child_product_with_parent_that_do_not_exist()
    {
            $payload['name'] = 'test-name';
            $payload['slug'] = 'test-slug';
            $payload['description'] = 'Test description';
            $payload['image'] = 'test-image';
            $payload['enabled'] = 1;
            $payload['parent_id'] = 100;

            $response = $this->withToken("Bearer {$this->token}")
                ->postJson('/api/products', $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains('Category parent id does not exist', $errorResponse['errors']['message']);
    }

    public function test_attempt_to_create_parent_product_ok()
    {
        $categoryPayload['name'] = 'test category';
        $categoryPayload['slug'] = 'test-category-slug';
        $categoryPayload['image'] = 'test-category-image';
        $categoryPayload['description'] = 'test category description';
        $categoryPayload['enabled'] = 1;
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);

        $payload['sku'] = 'test-sku';
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['category_id'] = $categoryData['data']['id'];
        $payload['enabled'] = 1;

        $response = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $payload);

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertSame(1, $data['data']['id']);
        $this->assertSame($payload['name'], $data['data']['name']);
        $this->assertSame($payload['slug'], $data['data']['slug']);
    }

    public function test_attempt_to_create_parent_and_child_product_ok()
    {
        $categoryPayload['name'] = 'test category';
        $categoryPayload['slug'] = 'test-category-slug';
        $categoryPayload['image'] = 'test-category-image';
        $categoryPayload['description'] = 'test category description';
        $categoryPayload['enabled'] = 1;
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);

        $payload['sku'] = 'test-sku';
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['category_id'] = $categoryData['data']['id'];
        $payload['enabled'] = 1;

        $response = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $payload);


        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertSame(1, $data['data']['id']);
        $this->assertSame($payload['name'], $data['data']['name']);
        $this->assertSame($payload['slug'], $data['data']['slug']);
    }

    public function test_delete_product_not_found()
    {
        $response = $this->withToken("Bearer {$this->token}")
            ->deleteJson('/api/products/111');
        $errorResponse = json_decode($response->getContent(), true);
        $response->assertStatus(400);
        $this->assertSame('given product id param does not exist', $errorResponse['errors']['message']);
    }


    public function test_delete_product_ok()
    {
        $categoryPayload['name'] = 'test category';
        $categoryPayload['slug'] = 'test-category-slug';
        $categoryPayload['image'] = 'test-category-image';
        $categoryPayload['description'] = 'test category description';
        $categoryPayload['enabled'] = 1;
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);

        $payload['sku'] = 'test-sku';
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['category_id'] = $categoryData['data']['id'];
        $payload['enabled'] = 1;

        $createResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $payload);
        $data = json_decode($createResponse->getContent(), true);
        $id = $data['data']['id'];

        $response = $this->withToken("Bearer {$this->token}")
            ->deleteJson('/api/products/' . $id);
        $errorResponse = json_decode($response->getContent(), true);
        $response->assertStatus(200);
    }

    public function test_attempt_to_update_parent_product_with_errors()
    {
        $categoryPayload['name'] = 'test category';
        $categoryPayload['slug'] = 'test-category-slug';
        $categoryPayload['image'] = 'test-category-image';
        $categoryPayload['description'] = 'test category description';
        $categoryPayload['enabled'] = 1;
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);

        $payload['sku'] = 'test-sku';
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['category_id'] = $categoryData['data']['id'];
        $payload['enabled'] = 1;

        $createResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $payload);

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
                ->putJson('/api/products/'.$id, $payload);

            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_update_root_product_ok()
    {
        $categoryPayload['name'] = 'test category';
        $categoryPayload['slug'] = 'test-category-slug';
        $categoryPayload['image'] = 'test-category-image';
        $categoryPayload['description'] = 'test category description';
        $categoryPayload['enabled'] = 1;
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);

        $payload['sku'] = 'test-sku';
        $payload['name'] = 'test-name';
        $payload['slug'] = 'test-slug';
        $payload['description'] = 'Test description';
        $payload['image'] = 'test-image';
        $payload['category_id'] = $categoryData['data']['id'];
        $payload['enabled'] = 1;

        $createResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $payload);

        $createResponse->assertStatus(200);
        $data = json_decode($createResponse->getContent(), true);
        $id = $data['data']['id'];

        $payloadUpdate['sku'] = 'test-sku-update';
        $payloadUpdate['name'] = 'test-name-update';
        $payloadUpdate['slug'] = 'test-slug-update';
        $payloadUpdate['description'] = 'Test description-update';
        $payloadUpdate['image'] = 'test-image-update';
        $payloadUpdate['enabled'] = 0;
        $payloadUpdate['category_id'] = $categoryData['data']['id'];

        $updateResponse = $this->withToken("Bearer {$this->token}")
                ->putJson('/api/products/'.$id, $payloadUpdate);

        $updateResponse->assertStatus(200);
        $data = json_decode($updateResponse->getContent(), true);
        $this->stringContains($payloadUpdate['name'], $data['data']['name']);
    }

}
