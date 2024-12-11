<?php

namespace Arneon\LaravelCategories\Application\UseCases;

use Arneon\LaravelCategories\Domain\Contracts\UseCases\FindById;
use Arneon\LaravelCategories\Domain\Repositories\Repository as RepositoryInterface;
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
