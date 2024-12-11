<?php

namespace Arneon\LaravelCategories\Infrastructure\Listeners;

use Arneon\LaravelCategories\Domain\Events\EntityUpdated as EventEntity;
use Arneon\LaravelCategories\Infrastructure\Models\Category as Model;
use Arneon\LaravelCategories\Infrastructure\Persistence\Redis\RedisRepository;
use Arneon\LaravelCategories\Infrastructure\Helpers\CategoriesHelper as PackageHelper;
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
        $categoryModel = Model::find($entity['id']);
        $categoryRedis = $this->buildCategoryArray($categoryModel);
        $this->redis->update($entity['id'], $categoryRedis);

        logger()->info('HandleEntityUpdated: ', ['entity' => $entity]);
    }
}
