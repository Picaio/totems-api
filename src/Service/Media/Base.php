<?php

declare(strict_types=1);

namespace App\Service\Media;

use App\Entity\Media;
use App\Repository\MediaRepository;
use App\Service\BaseService;
use App\Service\RedisService;
use Respect\Validation\Validator as v;

abstract class Base extends BaseService
{
    private const REDIS_KEY = 'media:%s:user:%s';

    public function __construct(
        protected MediaRepository $mediaRepository,
        protected RedisService $redisService
    ) {
    }

    protected function getMediaRepository(): MediaRepository
    {
        return $this->mediaRepository;
    }

    protected static function validateMediaName(string $name): string
    {
        if (! v::length(1, 100)->validate($name)) {
            throw new \App\Exception\Media('Invalid name.', 400);
        }

        return $name;
    }

    protected static function validateMediaStatus(int $status): int
    {
        if (! v::numeric()->between(0, 1)->validate($status)) {
            throw new \App\Exception\Media('Invalid status', 400);
        }

        return $status;
    }

    protected function getMediaFromCache(int $mediaId, int $userId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $mediaId, $userId);
        $key = $this->redisService->generateKey($redisKey);
        if ($this->redisService->exists($key)) {
            $media = $this->redisService->get($key);
        } else {
            $media = $this->getMediaFromDb($mediaId, $userId)->toJson();
            $this->redisService->setex($key, $media);
        }

        return $media;
    }

    protected function getMediaFromDb(int $mediaId, int $userId): Media
    {
        return $this->getMediaRepository()->checkAndGetMedia($mediaId, $userId);
    }

    protected function saveInCache(int $mediaId, int $userId, object $media): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $mediaId, $userId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->setex($key, $media);
    }

    protected function deleteFromCache(int $mediaId, int $userId): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $mediaId, $userId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->del([$key]);
    }
}
