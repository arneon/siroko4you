<?php

namespace Arneon\LaravelProducts\Application\UseCases;

use Arneon\LaravelProducts\Domain\Contracts\UseCases\FindByField;
use Arneon\LaravelProducts\Domain\Repositories\Repository as RepositoryInterface;
class FindByFieldUseCase implements FindByField
{
    public function __construct(
        private readonly RepositoryInterface $repository,
    )
    {
    }
    public function __invoke(string $field, mixed $fieldValue): array
    {
        return $this->repository->findByField($fieldValue, $field);
    }


}
