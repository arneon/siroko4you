<?php

namespace Arneon\LaravelCategories\Infrastructure\Persistence\Redis;

use Arneon\LaravelCategories\Domain\Repositories\Repository as RepositoryInterface;
use Illuminate\Support\Facades\Redis;

class RedisRepository implements RepositoryInterface
{
    private const REDIS_CATEGORY_KEY = 'categories';

    public function findAll()
    {
        $rows = Redis::get(self::REDIS_CATEGORY_KEY);
        if ($rows) {
            $dataArray = json_decode($rows, true);
            return $dataArray;
        }

        return [];
    }

    public function findById(int $id)
    {

    }

    public function create(array $data)
    {
        $rows = Redis::get(self::REDIS_CATEGORY_KEY);
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

        Redis::set(self::REDIS_CATEGORY_KEY, json_encode($rowsArray));
    }

    public function update($id, array $data)
    {
        $rows = Redis::get(self::REDIS_CATEGORY_KEY);
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

        Redis::set(self::REDIS_CATEGORY_KEY, json_encode($rowsArray));
    }

    public function delete($id)
    {
        $rows = Redis::get(self::REDIS_CATEGORY_KEY);
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

        Redis::set(self::REDIS_CATEGORY_KEY, json_encode($filteredRows));
    }

    public function replace(array $data)
    {
        return is_array($data) ? Redis::set(self::REDIS_CATEGORY_KEY, json_encode($data)) : false;
    }

    public function findByField(string $field, mixed $fieldValue)
    {
        // NOTE: It is not necessary to Implement the method in this repository.
    }
}

