<?php

namespace Arneon\LaravelUsers\Application\UseCases;

use Arneon\LaravelUsers\Domain\Contracts\UseCases\Delete as UseCaseInterface;
use Arneon\LaravelUsers\Domain\Repositories\Repository;
use Illuminate\Http\JsonResponse;

class DeleteUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly Repository $repository
    ){}

    public function __invoke(int $id): array
    {
        return $this->repository->delete($id);
    }
}
