<?php

namespace Arneon\LaravelCategories\Tests;

use Tests\TestCase as BaseTestCase;
use Arneon\LaravelUsers\Infrastructure\Providers\PackageServiceProvider as UsersServiceProvider;
use Arneon\LaravelCategories\Infrastructure\Providers\PackageServiceProvider as CategoriesServiceProvider;

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
}
