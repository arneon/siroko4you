<?php

namespace Arneon\LaravelCategories\Application\UseCases;

use Arneon\LaravelCategories\Domain\Contracts\UseCases\Delete as UseCaseInterface;
use Arneon\LaravelCategories\Domain\Repositories\Repository;
use Arneon\LaravelCategories\Domain\Events\EntityDeleted as EntityDeleted;
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
            'message' => "Category or Sub-category deleted successfully",
        ];
    }
}
