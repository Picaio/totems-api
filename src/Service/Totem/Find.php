<?php

declare(strict_types=1);

namespace App\Service\Totem;

final class Find extends Base
{
    /**
     * @return array<string>
     */
    public function getAll(): array
    {
        return $this->totemRepository->getTotems();
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
        if ($page < 1) {
            $page = 1;
        }
        if ($perPage < 1) {
            $perPage = self::DEFAULT_PER_PAGE_PAGINATION;
        }

        return $this->totemRepository->getTotemsByPage(
            $page,
            $perPage,
            $name,
            $description
        );
    }

    public function getOne(int $totemId): object
    {
        if (self::isRedisEnabled() === true) {
            $totem = $this->getOneFromCache($totemId);
        } else {
            $totem = $this->getOneFromDb($totemId)->toJson();
        }

        return $totem;
    }
}
