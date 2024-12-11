<?php

namespace Arneon\LaravelShoppingCart\Domain\Repositories;

interface Repository
{
    public function findById(int $id);
    public function create(array $data);
    public function addItem(array $data);
    public function removeItem(array $data);
    public function update($id, array $data);
    public function delete($id);

}

