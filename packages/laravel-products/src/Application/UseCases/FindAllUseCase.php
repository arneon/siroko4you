<?php

namespace Arneon\LaravelProducts\Application\UseCases;

use Arneon\LaravelProducts\Domain\Contracts\UseCases\FindAll;
use Arneon\LaravelProducts\Domain\Repositories\Repository as RepositoryInterface;

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
