<?php

declare(strict_types=1);

namespace App\Service\Totem;

use App\Entity\Totem;
use App\Repository\TotemRepository;
use App\Service\BaseService;
use App\Service\RedisService;
use Respect\Validation\Validator as v;

abstract class Base extends BaseService
{
    private const REDIS_KEY = 'totem:%s';

    public function __construct(
        protected TotemRepository $totemRepository,
        protected RedisService $redisService
    ) {
    }

    protected static function validateTotemName(string $name): string
    {
        if (! v::length(1, 50)->validate($name)) {
            throw new \App\Exception\Totem('The name of the totem is invalid.', 400);
        }

        return $name;
    }

    protected function getOneFromCache(int $totemId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $totemId);
        $key = $this->redisService->generateKey($redisKey);
        if ($this->redisService->exists($key)) {
            $totem = $this->redisService->get($key);
        } else {
            $totem = $this->getOneFromDb($totemId)->toJson();
            $this->redisService->setex($key, $totem);
        }

        return $totem;
    }

    protected function getOneFromDb(int $totemId): Totem
    {
        return $this->totemRepository->checkAndGetTotem($totemId);
    }

    protected function saveInCache(int $id, object $totem): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $id);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->setex($key, $totem);
    }

    protected function deleteFromCache(int $totemId): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $totemId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->del([$key]);
    }
}
