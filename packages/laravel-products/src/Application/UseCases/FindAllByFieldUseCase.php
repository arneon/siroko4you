<?php

namespace Arneon\LaravelProducts\Application\UseCases;

use Arneon\LaravelProducts\Domain\Contracts\UseCases\FindAllByField;
use Arneon\LaravelProducts\Domain\Repositories\Repository as RepositoryInterface;
class FindAllByFieldUseCase implements FindAllByField
{
    public function __construct(
        private readonly RepositoryInterface $repository,
    )
    {
    }
    public function __invoke(string $field, mixed $fieldValue, string $operator = '='): array
    {
        return $this->repository->findAllByField($fieldValue, $field, $operator);
    }


}
