<?php

namespace Arneon\LaravelCategories\Infrastructure\Listeners;

use Arneon\LaravelCategories\Domain\Events\EntityCreated as EntityCreated;
use Arneon\LaravelCategories\Infrastructure\Models\Category as Model;
use Arneon\LaravelCategories\Infrastructure\Persistence\Redis\RedisRepository;
use Arneon\LaravelCategories\Infrastructure\Helpers\CategoriesHelper as PackageHelper;

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
        $categoryModel = Model::find($entity['id']);
        $categoryRedis = $this->buildCategoryArray($categoryModel);
        $this->redis->create($categoryRedis);

        logger()->info('HandleEntityCreated: ', ['entity' => $entity]);
    }
}
