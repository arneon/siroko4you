<?php

namespace Arneon\LaravelProducts\Infrastructure\Listeners;

use Arneon\LaravelProducts\Domain\Events\EntityCreated as EntityCreated;
use Arneon\LaravelProducts\Infrastructure\Models\Product as Model;
use Arneon\LaravelProducts\Infrastructure\Persistence\Redis\RedisRepository;
use Arneon\LaravelProducts\Infrastructure\Helpers\ProductsHelper as PackageHelper;

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
        $productRedis = $this->buildProductArray($productModel);
        $this->redis->create($productRedis);

        logger()->info('HandleEntityCreated: ', ['entity' => $entity]);
    }
}
