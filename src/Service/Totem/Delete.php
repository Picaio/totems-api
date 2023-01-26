<?php

declare(strict_types=1);

namespace App\Service\Totem;

final class Delete extends Base
{
    public function delete(int $totemId): void
    {
        $this->getOneFromDb($totemId);
        $this->totemRepository->deleteTotem($totemId);
        if (self::isRedisEnabled() === true) {
            $this->deleteFromCache($totemId);
        }
    }
}
