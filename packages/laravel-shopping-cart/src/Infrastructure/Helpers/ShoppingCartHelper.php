<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Helpers;

trait ShoppingCartHelper
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

    private function buildShoppingCartArray($model) : array
    {
        $redisModel = [
            'id' => $model->id,
            'cart_code' => $model->cart_code,
            'session_id' => $model->session_id,
            'items' => json_encode($model->items),
            'total_price' => $model->total_price,
            'status' => $model->status,
            'customer_id' => $model->customer_id,
        ];
        return $redisModel;
    }


}
