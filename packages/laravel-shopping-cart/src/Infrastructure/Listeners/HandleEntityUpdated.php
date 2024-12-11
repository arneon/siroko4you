<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Listeners;

use Arneon\LaravelShoppingCart\Domain\Events\EntityUpdated as EventEntity;
use Arneon\LaravelShoppingCart\Infrastructure\Models\ShoppingCart as Model;
use Arneon\LaravelShoppingCart\Infrastructure\Persistence\Redis\RedisRepository;
use Arneon\LaravelShoppingCart\Infrastructure\Helpers\ShoppingCartHelper as PackageHelper;
class HandleEntityUpdated
{
    use PackageHelper;
    private $redis;
    public function __construct(
        private RedisRepository $redisRepository
    )
    {
        $this->redis = $redisRepository;

    }
    public function handle(EventEntity $event) :void
    {
        $entity = $event->entity;
        $productModel = Model::find($entity['id']);
        $productRedis = $this->buildShoppingCartArray($productModel);
        $this->redis->update($entity['id'], $productRedis);
        logger()->info('HandleEntityUpdated: ', ['entity' => $entity]);
    }
}
