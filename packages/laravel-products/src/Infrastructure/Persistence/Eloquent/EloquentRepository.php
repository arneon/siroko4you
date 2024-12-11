<?php

namespace Arneon\LaravelProducts\Infrastructure\Persistence\Eloquent;

use Arneon\LaravelProducts\Domain\Repositories\Repository as RepositoryInterface;
use Arneon\LaravelProducts\Infrastructure\Models\Product as Model;
use Illuminate\Support\Facades\DB;


class EloquentRepository implements RepositoryInterface
{

    public function findByField(mixed $fieldValue, mixed $field = 'id')
    {
        try {
            $model = new Model();
            $fields = array_merge($model->getFillable(), ['id']);
            if(!in_array($field, $fields))
            {
                throw new \Exception('Field does not exist');
            }

            $model = $model->where($field, $fieldValue)->first();
            return $model ? $model->toArray() : [];
        }
        catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function findAllByField(mixed $fieldValue, mixed $field = 'id', string $operator = '=')
    {
        try {
            $model = new Model();
            $fields = array_merge($model->getFillable(), ['id']);
            if(!in_array($field, $fields))
            {
                throw new \Exception('Field does not exist');
            }

            return $model->where($field, $fieldValue)->get()->toArray();
        }
        catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function findAll()
    {
        return Model::all();
    }
    public function findById(int $id)
    {
        $model = Model::where('id', $id)->first();

        if($model)
        {
            $model = $model->toArray();
            return $model;
        }
        else{
            return [];
        }
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $model = new Model();
            $model->fill($data);
            $model->save();
            DB::commit();
            return $model->toArray();
        }catch (\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
        }
    }

    public function update($id, array $data)
    {
        $model = Model::where('id', $id)->first();
        if($model)
        {
            DB::beginTransaction();
            try {

                $model->fill($data);
                $model->save();
                DB::commit();
                return $model->toArray();
            }catch (\Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        }
        else
        {
            throw new \Exception('Product not found', 404);
        }
    }

    public function delete($id)
    {
        $model = Model::where('id', $id)->first();
        if($model)
        {
            $associatedModels = Model::where('parent_id', $id)->get()->toArray();
            if(!empty($associatedModels))
            {
                throw new \Exception('This product has a child at least. it cannot be deleted', 400);
            }

            $model->delete();
            return ['message' => 'Product deleted successfully.'];
        }
        else
        {
            throw new \Exception('Product not found', 404);
        }
    }
}

