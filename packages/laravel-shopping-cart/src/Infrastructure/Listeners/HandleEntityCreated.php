<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Listeners;

use Arneon\LaravelShoppingCart\Domain\Events\EntityCreated as EntityCreated;
use Arneon\LaravelShoppingCart\Infrastructure\Models\ShoppingCart as Model;
use Arneon\LaravelShoppingCart\Infrastructure\Persistence\Redis\RedisRepository;
use Arneon\LaravelShoppingCart\Infrastructure\Helpers\ShoppingCartHelper as PackageHelper;

class HandleEntityCreated
{
    use PackageHelper;
    private $redis;
    public function __construct(
        private readonly RedisRepository $redisRepository,
    )
    {
        $this->redis = $redisRepository;
    }

    public function handle(EntityCreated $event) :void
    {
        $entity = $event->entity;
        $productModel = Model::find($entity['id']);
        $productRedis = $this->buildShoppingCartArray($productModel);
        $this->redis->create($productRedis);

        logger()->info('HandleEntityCreated: ', ['entity' => $entity]);
    }
}
