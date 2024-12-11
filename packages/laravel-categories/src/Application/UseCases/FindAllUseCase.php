<?php

namespace Arneon\LaravelCategories\Application\UseCases;

use Arneon\LaravelCategories\Domain\Contracts\UseCases\FindAll;
use Arneon\LaravelCategories\Domain\Repositories\Repository as RepositoryInterface;

class FindAllUseCase implements FindAll
{
    public function __construct(
        private readonly RepositoryInterface $repository,
    )
    {
    }
    public function __invoke(): array
    {
        return $this->repository->findAll();
    }


}
