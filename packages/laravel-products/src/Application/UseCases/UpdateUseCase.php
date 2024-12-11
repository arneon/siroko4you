<?php

namespace Arneon\LaravelProducts\Application\UseCases;

use Arneon\LaravelProducts\Domain\Contracts\UseCases\Update as UseCaseInterface;
use Arneon\LaravelProducts\Domain\Repositories\Repository;
use Arneon\LaravelProducts\Domain\Events\EntityUpdated as EntityUpdated;
use Illuminate\Support\Facades\Event;

class UpdateUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly Repository $repository
    ){}

    public function __invoke(int $id, array $request): array
    {

        try{
            $model = $this->repository->update($id, $request);
            Event::dispatch(new EntityUpdated($model));
            logger()->info('UpdateUseCase: ', ['model' => $model]);

            return $model;
        }catch (\Exception $e){
            logger()->error('UpdateUseCase Error: ', ['error' => $e->getMessage()]);
            return [$e->getMessage()];
        }

    }
}
