<?php

namespace Arneon\LaravelShoppingCart\Application\UseCases;

use Arneon\LaravelShoppingCart\Domain\Contracts\UseCases\AddItem as UseCaseInterface;
use Arneon\LaravelShoppingCart\Domain\Repositories\Repository;
use Arneon\LaravelShoppingCart\Domain\Events\EntityCreated as EntityCreated;
use Illuminate\Support\Facades\Event;

class AddItemUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly Repository $repository,
    ){

    }

    public function __invoke(array $request): array
    {
        try{
            $model = $this->repository->addItem($request);

            Event::dispatch(new EntityCreated($model));
            logger()->info('AddItemUseCase: ', ['model' => $model]);

            return $model;
        }catch (\Exception $e){
            logger()->error('AddItemUseCase Error: ', ['error' => $e->getMessage()]);
            return [$e->getMessage()];
        }
    }
}
