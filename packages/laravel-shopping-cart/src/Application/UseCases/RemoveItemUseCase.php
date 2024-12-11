<?php

namespace Arneon\LaravelShoppingCart\Application\UseCases;

use Arneon\LaravelShoppingCart\Domain\Contracts\UseCases\RemoveItem as UseCaseInterface;
use Arneon\LaravelShoppingCart\Domain\Repositories\Repository;
use Arneon\LaravelShoppingCart\Domain\Events\EntityDeleted as EntityDeleted;
use Illuminate\Support\Facades\Event;


class RemoveItemUseCase implements UseCaseInterface
{
    public function __construct(
        private readonly Repository $repository
    ){}

    public function __invoke(array $request): array
    {
        try{
            $model = $this->repository->removeItem($request);

            Event::dispatch(new EntityDeleted(['id' => $request['product_id']]));

            logger()->info('RemoveItemUseCase: ', ['id' => $request['product_id']]);

            return [
                'message' => "Item removed from shopping cart successfully",
            ];
        }catch(\Exception $e)
        {
            logger()->error('RemoveItemUseCase Error: ', ['error' => $e->getMessage()]);
            return [$e->getMessage()];
        }

    }
}
