<?php

namespace Arneon\LaravelShoppingCart\Tests;

use Tests\TestCase as BaseTestCase;
use Arneon\LaravelUsers\Infrastructure\Providers\PackageServiceProvider as UsersServiceProvider;
use Arneon\LaravelProducts\Infrastructure\Providers\PackageServiceProvider as CategoriesServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected string $token='';
    protected function getPackageProviders($app)
    {
        return [
            UsersServiceProvider::class,
            CategoriesServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh')->run();
        $this->createUserToken();
    }

    protected function createUserToken() : void
    {
        $user = new \App\Models\User();
        $user->name = 'Test User';
        $user->email = 'api-test-user@test.com';
        $user->password = \Illuminate\Support\Facades\Hash::make('12345678');
        $user->save();
        $token = $user->createToken('apiTest');
        $this->token = $token->plainTextToken;
    }

    protected function buildCategoryTest() : array
    {
        $categoryPayload['name'] = 'test category';
        $categoryPayload['slug'] = 'test-category-slug';
        $categoryPayload['image'] = 'test-category-image';
        $categoryPayload['description'] = 'test category description';
        $categoryPayload['enabled'] = 1;
        return $categoryPayload;
    }

    protected function buildParentProductTest($categoryId) : array
    {
        $payload['sku'] = 'test-sku-parent';
        $payload['name'] = 'test-name-parent';
        $payload['slug'] = 'test-slug-parent';
        $payload['description'] = 'Test description parent';
        $payload['image'] = 'test-image-parent';
        $payload['category_id'] = $categoryId;
        $payload['enabled'] = 1;
        $payload['price'] = 0;
        $payload['stock'] = 0;
        $payload['parent_id'] = null;
        return $payload;
    }

    protected function buildChildProductTest($categoryId, $parent) : array
    {
        $payload['sku'] = 'test-sku-child';
        $payload['name'] = 'test-name-child';
        $payload['slug'] = 'test-slug-child';
        $payload['description'] = 'Test description child';
        $payload['image'] = 'test-image-child';
        $payload['category_id'] = $categoryId;
        $payload['enabled'] = 1;
        $payload['price'] = 10;
        $payload['stock'] = 5;
        $payload['parent_id'] = $parent;

        return $payload;
    }
}
