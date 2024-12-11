<?php

namespace Arneon\LaravelProducts\Application\UseCases;

use Arneon\LaravelProducts\Domain\Contracts\UseCases\Delete as UseCaseInterface;
use Arneon\LaravelProducts\Domain\Repositories\Repository;
use Arneon\LaravelProducts\Domain\Events\EntityDeleted as EntityDeleted;
use Illuminate\Support\Facades\Event;


class DeleteUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly Repository $repository
    ){}

    public function __invoke(int $id): array
    {
        $model = $this->repository->delete($id);

        Event::dispatch(new EntityDeleted(['id' => $id]));

        logger()->info('DeleteUseCase: ', ['id' => $id]);

        return [
            'message' => "Product deleted successfully",
        ];
    }
}
