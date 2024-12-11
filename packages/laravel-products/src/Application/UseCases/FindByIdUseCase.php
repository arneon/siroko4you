<?php

namespace Arneon\LaravelProducts\Application\UseCases;

use Arneon\LaravelProducts\Domain\Contracts\UseCases\FindById;
use Arneon\LaravelProducts\Domain\Repositories\Repository as RepositoryInterface;
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
