<?php

namespace Arneon\LaravelCategories\Infrastructure\Listeners;

use Arneon\LaravelCategories\Domain\Events\EntityDeleted as EntityDeleted;
use Arneon\LaravelCategories\Infrastructure\Persistence\Redis\RedisRepository;

class HandleEntityDeleted
{
    private $redis;
    public function __construct(
        private RedisRepository $redisRepository,
    )
    {
        $this->redis = $redisRepository;
    }
    public function handle(EntityDeleted $event) :void
    {
        $entity = $event->entity;

        $rows = $this->redis->delete($entity['id']);

        logger()->info('HandleEntityDeleted: ', ['entity' => $entity]);
    }
}
