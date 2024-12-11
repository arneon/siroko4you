<?php

namespace Arneon\LaravelShoppingCart\Application\UseCases;

use Arneon\LaravelShoppingCart\Domain\Contracts\UseCases\FindById;
use Arneon\LaravelShoppingCart\Domain\Repositories\Repository as RepositoryInterface;
class FindByIdUseCase implements FindById
{
    public function __construct(
        private readonly RepositoryInterface $repository,
    )
    {
    }
    public function __invoke(int $id): array
    {
        return $this->repository->findById($id);
    }


}
