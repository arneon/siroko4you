<?php

namespace Arneon\LaravelUsers\Infrastructure\Helpers;

trait UserHelper
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
}
