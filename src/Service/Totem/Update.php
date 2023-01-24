<?php

declare(strict_types=1);

namespace App\Service\Totem;

use App\Entity\Totem;

final class Update extends Base
{
    /**
     * @param array<string> $input
     */
    public function update(array $input, int $totemId): object
    {
        $totem = $this->getOneFromDb($totemId);
        $data = json_decode((string) json_encode($input), false);
        if (isset($data->name)) {
            $totem->updateName(self::validateTotemName($data->name));
        }
        if (isset($data->description)) {
            $totem->updateDescription($data->description);
        }
        /** @var Totem $totems */
        $totems = $this->totemRepository->updateTotem($totem);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache($totems->getId(), $totems->toJson());
        }

        return $totems->toJson();
    }
}
