<?php

namespace Arneon\LaravelUsers\Infrastructure\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'test user',
                    'email' => 'test@test.com',
                    'password' => HASH::make('12345678'),
                ]
            ]
        );
    }

}
