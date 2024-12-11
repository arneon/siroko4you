<?php

namespace Arneon\LaravelShoppingCart\Application\UseCases;

use Arneon\LaravelShoppingCart\Domain\Contracts\UseCases\UpdateItemQuantity as UseCaseInterface;
use Arneon\LaravelShoppingCart\Domain\Repositories\Repository;
use Arneon\LaravelShoppingCart\Domain\Events\EntityUpdated as EntityUpdated;
use Illuminate\Support\Facades\Event;

class UpdateItemQuantityUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly Repository $repository
    ){}

    public function __invoke(array $request): array
    {
        try{
            $model = $this->repository->updateItemQuantity($request);
            Event::dispatch(new EntityUpdated($model));
            logger()->info('UpdateUseCase: ', ['model' => $model]);

            return $model;
        }catch (\Exception $e){
            logger()->error('UpdateUseCase Error: ', ['error' => $e->getMessage()]);
            return [$e->getMessage()];
        }

    }
}
