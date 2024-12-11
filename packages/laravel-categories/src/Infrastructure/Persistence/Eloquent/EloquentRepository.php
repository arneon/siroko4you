<?php

namespace Arneon\LaravelCategories\Infrastructure\Persistence\Eloquent;

use Arneon\LaravelCategories\Domain\Repositories\Repository as RepositoryInterface;
use Arneon\LaravelCategories\Infrastructure\Models\Category as Model;
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
            throw new \Exception('Category not found', 404);
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
                throw new \Exception('This category has another associated categories. it cannot be deleted', 400);
            }

            $model->delete();
            return ['message' => 'Category deleted successfully.'];
        }
        else
        {
            throw new \Exception('Category not found', 404);
        }


    }
}

