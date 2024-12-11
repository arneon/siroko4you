<?php

namespace Arneon\LaravelShoppingCart\Application\UseCases;

use Arneon\LaravelShoppingCart\Domain\Contracts\UseCases\FindByField;
use Arneon\LaravelShoppingCart\Domain\Repositories\Repository as RepositoryInterface;
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
