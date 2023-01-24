<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Totem;

final class TotemRepository extends BaseRepository
{
    public function checkAndGetTotem(int $totemId): Totem
    {
        $query = 'SELECT * FROM `totems` WHERE `id` = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $totemId);
        $statement->execute();
        $totem = $statement->fetchObject(Totem::class);
        if (! $totem) {
            throw new \App\Exception\Totem('Totem not found.', 404);
        }

        return $totem;
    }

    /**
     * @return array<string>
     */
    public function getTotems(): array
    {
        $query = 'SELECT * FROM `totems` ORDER BY `id`';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll();
    }

    public function getQueryTotemsByPage(): string
    {
        return "
            SELECT *
            FROM `totems`
            WHERE `name` LIKE CONCAT('%', :name, '%')
            AND `description` LIKE CONCAT('%', :description, '%')
            ORDER BY `id`
        ";
    }

    /**
     * @return array<string>
     */
    public function getTotemsByPage(
        int $page,
        int $perPage,
        ?string $name,
        ?string $description
    ): array {
        $params = [
            'name' => is_null($name) ? '' : $name,
            'description' => is_null($description) ? '' : $description,
        ];
        $query = $this->getQueryTotemsByPage();
        $statement = $this->database->prepare($query);
        $statement->bindParam('name', $params['name']);
        $statement->bindParam('description', $params['description']);
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

    public function createTotem(Totem $totem): Totem
    {
        $query = '
            INSERT INTO `totems`
                (`name`, `description`)
            VALUES
                (:name, :description)
        ';
        $statement = $this->database->prepare($query);
        $name = $totem->getName();
        $desc = $totem->getDescription();
        $statement->bindParam(':name', $name);
        $statement->bindParam(':description', $desc);
        $statement->execute();

        return $this->checkAndGetTotem((int) $this->database->lastInsertId());
    }

    public function updateTotem(Totem $totem): Totem
    {
        $query = '
            UPDATE `totems`
            SET `name` = :name, `description` = :description
            WHERE `id` = :id
        ';
        $statement = $this->database->prepare($query);
        $id = $totem->getId();
        $name = $totem->getName();
        $desc = $totem->getDescription();
        $statement->bindParam(':id', $id);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':description', $desc);
        $statement->execute();

        return $this->checkAndGetTotem((int) $id);
    }

    public function deleteTotem(int $totemId): void
    {
        $query = 'DELETE FROM `totems` WHERE `id` = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $totemId);
        $statement->execute();
    }
}
