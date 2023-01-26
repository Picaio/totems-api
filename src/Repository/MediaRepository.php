<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Media;

final class MediaRepository extends BaseRepository
{
    public function getQueryMediasByPage(): string
    {
        return "
            SELECT *
            FROM `media`
            WHERE `userId` = :userId
            AND `name` LIKE CONCAT('%', :name, '%')
            AND `description` LIKE CONCAT('%', :description, '%')
            AND `status` LIKE CONCAT('%', :status, '%')
            ORDER BY `id`
        ";
    }

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
        $params = [
            'userId' => $userId,
            'name' => is_null($name) ? '' : $name,
            'description' => is_null($description) ? '' : $description,
            'status' => is_null($status) ? '' : $status,
        ];
        $query = $this->getQueryMediasByPage();
        $statement = $this->database->prepare($query);
        $statement->bindParam('userId', $params['userId']);
        $statement->bindParam('name', $params['name']);
        $statement->bindParam('description', $params['description']);
        $statement->bindParam('status', $params['status']);
        $statement->execute();
        $total = $statement->rowCount();

        return $this->getResultsWithPagination(
            $query,
            $page,
            $perPage,
            $params,
            $total
        );
    }

    public function checkAndGetMedia(int $mediaId, int $userId): Media
    {
        $query = '
            SELECT * FROM `media` WHERE `id` = :id AND `userId` = :userId
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $mediaId);
        $statement->bindParam('userId', $userId);
        $statement->execute();
        $media = $statement->fetchObject(Media::class);
        if (! $media) {
            throw new \App\Exception\Media('Media not found.', 404);
        }

        return $media;
    }

    /**
     * @return array<string>
     */
    public function getAllMedias(): array
    {
        $query = 'SELECT * FROM `media` ORDER BY `id`';
        $statement = $this->getDb()->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll();
    }

    public function create(Media $media): Media
    {
        $query = '
            INSERT INTO `media`
                (`name`, `description`, `status`, `userId`)
            VALUES
                (:name, :description, :status, :userId)
        ';
        $statement = $this->getDb()->prepare($query);
        $name = $media->getName();
        $desc = $media->getDescription();
        $status = $media->getStatus();
        $userId = $media->getUserId();
        $statement->bindParam('name', $name);
        $statement->bindParam('description', $desc);
        $statement->bindParam('status', $status);
        $statement->bindParam('userId', $userId);
        $statement->execute();

        $mediaId = (int) $this->database->lastInsertId();

        return $this->checkAndGetMedia($mediaId, (int) $userId);
    }

    public function update(Media $media): Media
    {
        $query = '
            UPDATE `media`
            SET `name` = :name, `description` = :description, `status` = :status
            WHERE `id` = :id AND `userId` = :userId
        ';
        $statement = $this->getDb()->prepare($query);
        $id = $media->getId();
        $name = $media->getName();
        $desc = $media->getDescription();
        $status = $media->getStatus();
        $userId = $media->getUserId();
        $statement->bindParam('id', $id);
        $statement->bindParam('name', $name);
        $statement->bindParam('description', $desc);
        $statement->bindParam('status', $status);
        $statement->bindParam('userId', $userId);
        $statement->execute();

        return $this->checkAndGetMedia((int) $id, (int) $userId);
    }

    public function delete(int $mediaId, int $userId): void
    {
        $query = 'DELETE FROM `media` WHERE `id` = :id AND `userId` = :userId';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $mediaId);
        $statement->bindParam('userId', $userId);
        $statement->execute();
    }
}
