<?php

namespace Arneon\LaravelProducts\Infrastructure\Helpers;

trait ProductsHelper
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

    private function buildProductArray($productModel) : array
    {
        $redisModel = [
            'id' => $productModel->id,
            'sku' => $productModel->sku,
            'slug' => $productModel->slug,
            'name' => $productModel->name,
            'description' => $productModel->description,
            'parent_id' => $productModel->parent_id,
            'category_id' => $productModel->category_id,
            'enabled' => $productModel->enabled,
            'image' => $productModel->image,
            'size' => $productModel->size,
            'color' => $productModel->color,
            'price' => $productModel->price,
            'stock' => $productModel->stock,
        ];
        return $redisModel;
    }


}
