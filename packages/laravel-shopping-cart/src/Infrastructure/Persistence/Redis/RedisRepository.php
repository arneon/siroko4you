<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Persistence\Redis;

use Arneon\LaravelShoppingCart\Domain\Repositories\Repository as RepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Arr;

class RedisRepository implements RepositoryInterface
{
    private const REDIS_KEY = 'shopping_cart';

    public function findAll()
    {
        $rows = Redis::get(self::REDIS_KEY);
        if ($rows) {
            $dataArray = json_decode($rows, true);
            return $this->buildParentChildrenProductArray($dataArray);
        }

        return [];
    }

    private function buildParentChildrenProductArray($products) : array
    {
        $productsArray = [];
        $parents = collect($products)->where('parent_id', null);
        $children = collect($products)->where('parent_id', '!=', null);

        foreach ($parents as $parent) {
            $parent['children'] = $children->where('parent_id', $parent['id'])->values()->all();
            $productsArray[] = $parent;
        }
        return $productsArray;
    }

    public function findById(int $id)
    {

    }

    public function create(array $data)
    {
        $rows = Redis::get(self::REDIS_KEY);
        $rowsArray = ($rows) ? json_decode($rows, true) : [];

        if (!empty($rowsArray)) {
            $founded = false;
            foreach ($rowsArray as &$row) {
                if ($row['id'] == $data['id']) {
                    $founded = true;
                    $row = $data;
                }
            }
            if(!$founded) {
                $rowsArray[] = $data;
            }
        }
        else
        {
            $rowsArray[] = $data;
        }

        Redis::set(self::REDIS_KEY, json_encode($rowsArray));
    }

    public function update($id, array $data)
    {
        $rows = Redis::get(self::REDIS_KEY);
        $rowsArray = ($rows) ? json_decode($rows, true) : [];

        if (!empty($rowsArray)) {
            $founded = false;
            foreach ($rowsArray as &$row) {
                if ($row['id'] == $id) {
                    $founded=true;
                    $row = $data;
                }
            }

            if(!$founded)
            {
                $rowsArray[] = $data;
            }
        }
        else
        {
            $rowsArray[] = $data;
        }

        Redis::set(self::REDIS_KEY, json_encode($rowsArray));
    }

    public function delete($id)
    {
        $rows = Redis::get(self::REDIS_KEY);
        $rowsArray = ($rows) ? json_decode($rows, true) : [];
        $filteredRows = [];

        if (!empty($rowsArray)) {
            foreach ($rowsArray as &$row)
            {
                if($row['id'] == $id)
                {
                    $filteredRows = array_values(array_filter($rowsArray, function ($row) use ($id) {
                        return $row['id'] != $id;
                }));
                }
            }
        }

        Redis::set(self::REDIS_KEY, json_encode($filteredRows));
    }

    public function replace(array $data)
    {
        return is_array($data) ? Redis::set(self::REDIS_KEY, json_encode($data)) : false;
    }

    public function findByField(mixed $fieldValue, string $field)
    {
        // NOTE: It is not necessary to Implement the method in this repository.
    }

    public function addItem(array $data)
    {
        // TODO: Implement addItem() method.
    }

    public function removeItem(array $data)
    {
        // TODO: Implement removeItem() method.
    }
}

