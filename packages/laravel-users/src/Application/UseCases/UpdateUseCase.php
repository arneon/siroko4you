<?php

namespace Arneon\LaravelUsers\Application\UseCases;

use Arneon\LaravelUsers\Domain\Contracts\UseCases\Update as UseCaseInterface;
use Arneon\LaravelUsers\Domain\Repositories\Repository;
class UpdateUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly Repository $repository
    ){}

    public function __invoke(int $id, array $request): array
    {
        return $this->repository->update($id, $request);
    }
}
