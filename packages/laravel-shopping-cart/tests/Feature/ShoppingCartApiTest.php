<?php

namespace Arneon\LaravelShoppingCart\Tests\Feature;

use Arneon\LaravelShoppingCart\Tests\TestCase;

class ShoppingCartApiTest extends TestCase
{

    public function test_attempt_to_add_item_to_shopping_cart_with_bad_product_id_field()
    {
        $array = [
            'payload' => [
                ['id' => ''],
                ['id' => '1234'],
                ['id' => 100]
            ],
            'errorMessage' => [
                'Item id is required.',
                'Item id must be an integer.',
                'Item id does not exist.',
            ],
        ];

        $index=0;
        foreach ($array['payload'] as $payload) {
            $payload['name'] = 'Test product name';
            $payload['price'] = 12.0;
            $payload['quantity'] = 1;

            $response = $this->withToken("Bearer {$this->token}")
                ->postJson('/api/shopping-cart', $payload);
            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_add_item_to_shopping_cart_with_bad_price_field()
    {
        $categoryPayload = $this->buildCategoryTest();
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);
        $categoryResponse->assertStatus(200);

        $productPayload = $this->buildParentProductTest($categoryData['data']['id']);
        $productResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $productPayload);
        $productData = json_decode($productResponse->getContent(), true);
        $productResponse->assertStatus(200);

        $array = [
            'payload' => [
                ['price' => ''],
                ['price' => '1234'],
                ['price' => -1]
            ],
            'errorMessage' => [
                'Item price is required',
                'Item price must be decimal number.',
                'Item price must be > 0.',
            ],
        ];

        $index=0;
        foreach ($array['payload'] as $payload) {
            $payload['id'] = $productData['data']['id'];
            $payload['name'] = $productData['data']['id'];
            $payload['quantity'] = 1;

            $response = $this->withToken("Bearer {$this->token}")
                ->postJson('/api/shopping-cart', $payload);
            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_attempt_to_add_item_to_shopping_cart_with_bad_quantity_field()
    {
        $categoryPayload = $this->buildCategoryTest();
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);
        $categoryResponse->assertStatus(200);

        $productPayload = $this->buildParentProductTest($categoryData['data']['id']);
        $productResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $productPayload);
        $productData = json_decode($productResponse->getContent(), true);
        $productResponse->assertStatus(200);

        $array = [
            'payload' => [
                ['quantity' => ''],
                ['quantity' => '1234'],
                ['quantity' => 0]
            ],
            'errorMessage' => [
                'Item quantity is required.',
                'Item quantity must be integer.',
                'Item quantity must be integer > 0.',
            ],
        ];

        $index=0;
        foreach ($array['payload'] as $payload) {
            $payload['id'] = $productData['data']['id'];
            $payload['name'] = $productData['data']['id'];
            $payload['price'] = $productData['data']['price'];

            $response = $this->withToken("Bearer {$this->token}")
                ->postJson('/api/shopping-cart', $payload);
            $errorResponse = json_decode($response->getContent(), true);
            $response->assertStatus(400);
            $this->assertNotEmpty($errorResponse);
            $this->assertArrayHasKey('errors', $errorResponse);
            $this->stringContains($array['errorMessage'][$index], $errorResponse['errors']['message']);
            $index++;
        }
    }

    public function test_add_item_to_shopping_cart_ok()
    {
        $categoryPayload = $this->buildCategoryTest();
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);
        $categoryResponse->assertStatus(200);

        $parentProductPayload = $this->buildParentProductTest($categoryData['data']['id']);
        $parentProductResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $parentProductPayload);
        $parentProductResponse->assertStatus(200);
        $parentProductData = json_decode($parentProductResponse->getContent(), true);

        $childProductPayload = $this->buildChildProductTest($categoryData['data']['id'], $parentProductData['data']['id']);
        $childProductResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $childProductPayload);
        $childProductResponse->assertStatus(200);
        $childProductData = json_decode($childProductResponse->getContent(), true);

        $payload['id'] = $childProductData['data']['id'];
        $payload['name'] = $childProductData['data']['name'];
        $payload['price'] = 10;
        $payload['quantity'] = 1;
        $payload['_token'] = 'test-session';

        $response = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/shopping-cart', $payload);

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertSame(1, $data['data']['id']);
        $this->assertSame($payload['_token'], $data['data']['session_id']);
        $this->assertSame(10, $data['data']['total_price']);
    }

    public function test_remove_item_from_shopping_cart_ok()
    {
        $categoryPayload = $this->buildCategoryTest();
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);
        $categoryResponse->assertStatus(200);

        $parentProductPayload = $this->buildParentProductTest($categoryData['data']['id']);
        $parentProductResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $parentProductPayload);
        $parentProductResponse->assertStatus(200);
        $parentProductData = json_decode($parentProductResponse->getContent(), true);

        $childProductPayload = $this->buildChildProductTest($categoryData['data']['id'], $parentProductData['data']['id']);
        $childProductResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $childProductPayload);
        $childProductResponse->assertStatus(200);
        $childProductData = json_decode($childProductResponse->getContent(), true);

        $payload['id'] = $childProductData['data']['id'];
        $payload['name'] = $childProductData['data']['name'];
        $payload['price'] = 10;
        $payload['quantity'] = 1;
        $payload['_token'] = 'test-session';

        $cartResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/shopping-cart', $payload);

        $cartResponse->assertStatus(200);
        $cartData = json_decode($cartResponse->getContent(), true);

        $response = $this->withToken("Bearer {$this->token}")
            ->putJson('/api/shopping-cart/'.$cartData['data']['cart_code'].'/'.$childProductData['data']['id'], $payload);
        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);
        $this->assertSame('Item removed from shopping cart successfully', $data['data']['message']);
    }

    public function test_update_shopping_cart_item_quantity_ok()
    {
        $categoryPayload = $this->buildCategoryTest();
        $categoryResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/categories', $categoryPayload);
        $categoryData = json_decode($categoryResponse->getContent(), true);
        $categoryResponse->assertStatus(200);

        $parentProductPayload = $this->buildParentProductTest($categoryData['data']['id']);
        $parentProductResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $parentProductPayload);
        $parentProductResponse->assertStatus(200);
        $parentProductData = json_decode($parentProductResponse->getContent(), true);

        $childProductPayload = $this->buildChildProductTest($categoryData['data']['id'], $parentProductData['data']['id']);
        $childProductResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/products', $childProductPayload);
        $childProductResponse->assertStatus(200);
        $childProductData = json_decode($childProductResponse->getContent(), true);

        $payload['id'] = $childProductData['data']['id'];
        $payload['name'] = $childProductData['data']['name'];
        $payload['price'] = 10;
        $payload['quantity'] = 1;
        $payload['_token'] = 'test-session';

        $cartResponse = $this->withToken("Bearer {$this->token}")
            ->postJson('/api/shopping-cart', $payload);

        $cartResponse->assertStatus(200);
        $cartData = json_decode($cartResponse->getContent(), true);

        $response = $this->withToken("Bearer {$this->token}")
            ->putJson('/api/shopping-cart/'.$cartData['data']['cart_code'].'/'.$childProductData['data']['id'].'/10', $payload);
        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(100, $data['data']['total_price']);
    }

    }
