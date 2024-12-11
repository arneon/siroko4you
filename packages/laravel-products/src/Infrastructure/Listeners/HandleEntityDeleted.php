<?php

namespace Arneon\LaravelProducts\Infrastructure\Listeners;

use Arneon\LaravelProducts\Domain\Events\EntityDeleted as EntityDeleted;
use Arneon\LaravelProducts\Infrastructure\Persistence\Redis\RedisRepository;

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