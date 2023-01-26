<?php

declare(strict_types=1);

namespace App\Service\Media;

use App\Entity\Media;

final class MediaService extends Base
{
    /**
     * @return array<string>
     */
    public function getMediasByPage(
        int $userId,
        int $page,
        int $perPage,
        ?string $name,
        ?string $description,
        ?string $status
    ): array {
        if ($page < 1) {
            $page = 1;
        }
        if ($perPage < 1) {
            $perPage = self::DEFAULT_PER_PAGE_PAGINATION;
        }

        return $this->getMediaRepository()->getMediasByPage(
            $userId,
            $page,
            $perPage,
            $name,
            $description,
            $status
        );
    }

    /**
     * @return array<string>
     */
    public function getAllMedias(): array
    {
        return $this->getMediaRepository()->getAllMedias();
    }

    public function getOne(int $taskId, int $userId): object
    {
        if (self::isRedisEnabled() === true) {
            $task = $this->getMediaFromCache($taskId, $userId);
        } else {
            $task = $this->getMediaFromDb($taskId, $userId)->toJson();
        }

        return $task;
    }

    /**
     * @param array<string> $input
     */
    public function create(array $input): object
    {
        $data = json_decode((string) json_encode($input), false);
        if (! isset($data->name)) {
            throw new \App\Exception\Media('The field "name" is required.', 400);
        }
        $mytask = new Media();
        $mytask->updateName(self::validateMediaName($data->name));
        $description = isset($data->description) ? $data->description : null;
        $mytask->updateDescription($description);
        $status = 0;
        if (isset($data->status)) {
            $status = self::validateMediaStatus($data->status);
        }
        $mytask->updateStatus($status);
        $userId = null;
        if (isset($data->decoded) && isset($data->decoded->sub)) {
            $userId = (int) $data->decoded->sub;
        }
        $mytask->updateUserId($userId);
        /** @var Media $task */
        $task = $this->getMediaRepository()->create($mytask);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache(
                $task->getId(),
                $task->getUserId(),
                $task->toJson()
            );
        }

        return $task->toJson();
    }

    /**
     * @param array<string> $input
     */
    public function update(array $input, int $taskId): object
    {
        $data = $this->validateMedia($input, $taskId);
        /** @var Media $task */
        $task = $this->getMediaRepository()->update($data);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache(
                $task->getId(),
                $data->getUserId(),
                $task->toJson()
            );
        }

        return $task->toJson();
    }

    public function delete(int $taskId, int $userId): void
    {
        $this->getMediaFromDb($taskId, $userId);
        $this->getMediaRepository()->delete($taskId, $userId);
        if (self::isRedisEnabled() === true) {
            $this->deleteFromCache($taskId, $userId);
        }
    }

    private function validateMedia(array $input, int $taskId): Media
    {
        $task = $this->getMediaFromDb($taskId, (int) $input['decoded']->sub);
        $data = json_decode((string) json_encode($input), false);
        if (! isset($data->name) && ! isset($data->status)) {
            throw new \App\Exception\Media('Enter the data to update the task.', 400);
        }
        if (isset($data->name)) {
            $task->updateName(self::validateMediaName($data->name));
        }
        if (isset($data->description)) {
            $task->updateDescription($data->description);
        }
        if (isset($data->status)) {
            $task->updateStatus(self::validateMediaStatus($data->status));
        }
        $userId = null;
        if (isset($data->decoded) && isset($data->decoded->sub)) {
            $userId = (int) $data->decoded->sub;
        }
        $task->updateUserId($userId);

        return $task;
    }
}
