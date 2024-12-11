<?php

namespace Arneon\LaravelProducts\Infrastructure\Listeners;

use Arneon\LaravelProducts\Domain\Events\EntityUpdated as EventEntity;
use Arneon\LaravelProducts\Infrastructure\Models\Product as Model;
use Arneon\LaravelProducts\Infrastructure\Persistence\Redis\RedisRepository;
use Arneon\LaravelProducts\Infrastructure\Helpers\ProductsHelper as PackageHelper;
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
        $productRedis = $this->buildProductArray($productModel);
        $this->redis->update($entity['id'], $productRedis);

        logger()->info('HandleEntityUpdated: ', ['entity' => $entity]);
    }
}
