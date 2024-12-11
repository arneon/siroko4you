<?php

namespace Arneon\LaravelCategories\Application\UseCases;

use Arneon\LaravelCategories\Domain\Contracts\UseCases\Create as UseCaseInterface;
use Arneon\LaravelCategories\Domain\Repositories\Repository;
use Arneon\LaravelCategories\Domain\Events\EntityCreated as EntityCreated;
use Illuminate\Support\Facades\Event;

class CreateUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly Repository $repository,
    ){

    }

    public function __invoke(array $request): array
    {
        try{
            $model = $this->repository->create($request);

            Event::dispatch(new EntityCreated($model));
            logger()->info('CreateUseCase: ', ['model' => $model]);

            return $model;
        }catch (\Exception $e){
            logger()->error('CreateUseCase Error: ', ['error' => $e->getMessage()]);
            return [$e->getMessage()];
        }
    }
}
