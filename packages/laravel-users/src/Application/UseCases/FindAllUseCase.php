<?php

namespace Arneon\LaravelUsers\Application\UseCases;

use Arneon\LaravelUsers\Domain\Contracts\UseCases\FindAll;
use Arneon\LaravelUsers\Domain\Repositories\Repository as RepositoryInterface;
class FindAllUseCase implements FindAll
{
    public function __construct(
        private readonly RepositoryInterface $repository
    )
    {
    }
    public function __invoke(): array
    {
        return $this->repository->findAll();
    }


}
