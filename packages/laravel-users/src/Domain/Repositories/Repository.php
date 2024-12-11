<?php

namespace Arneon\LaravelUsers\Domain\Repositories;

use Illuminate\Support\Facades\Request;

interface Repository
{
    public function findAll();
    public function findByField(mixed $fieldValue, string $field = 'id');
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function register(array $data);
    public function validateLogin(string $email, string $password);

}

