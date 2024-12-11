<?php

namespace Arneon\LaravelProducts\Domain\Repositories;

interface Repository
{
    public function findById(int $id);
    public function findByField(string $field, mixed $fieldValue);
    public function findAllByField(mixed $fieldValue, string $field = 'id', string $operator = '=');
    public function findAll();
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

}

