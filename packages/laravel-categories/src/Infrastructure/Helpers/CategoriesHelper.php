<?php

namespace Arneon\LaravelCategories\Infrastructure\Helpers;

trait CategoriesHelper
{
    public function setEntityValuesToModel($model, $entityArray)
    {
        foreach ($model->getFillable() as $field)
        {
            if(array_key_exists($field, $entityArray))
            {
                $model->{$field} = $entityArray[$field];
            }
        }
        return $model;
    }

    private function buildCategoryArray($categoryModel) : array
    {
        $redisCategory = [
            'id' => $categoryModel->id,
            'name' => $categoryModel->name,
            'slug' => $categoryModel->slug,
            'image' => $categoryModel->image,
            'description' => $categoryModel->description,
            'parent_id' => $categoryModel->parent_id,
            'enabled' => $categoryModel->enabled,
        ];
        return $redisCategory;
    }
}
