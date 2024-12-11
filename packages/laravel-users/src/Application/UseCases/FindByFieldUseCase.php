<?php

namespace Arneon\LaravelUsers\Application\UseCases;

use Arneon\LaravelUsers\Domain\Contracts\UseCases\FindByField;
use Arneon\LaravelUsers\Domain\Repositories\Repository as RepositoryInterface;
class FindByFieldUseCase implements FindByField
{
    public function __construct(
        private readonly RepositoryInterface $repository,
    )
    {
    }
    public function __invoke(string $field, mixed $fieldValue): array
    {
        return $this->repository->findByField($field, $fieldValue);
    }


}
