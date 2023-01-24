<?php

declare(strict_types=1);

namespace App\Service\Totem;

use App\Entity\Totem;

final class Create extends Base
{
    /**
     * @param array<string> $input
     */
    public function create(array $input): object
    {
        $data = json_decode((string) json_encode($input), false);
        if (! isset($data->name)) {
            throw new \App\Exception\Totem('Invalid data: name is required.', 400);
        }
        $mytotem = new Totem();
        $mytotem->updateName(self::validateTotemName($data->name));
        $description = isset($data->description) ? $data->description : null;
        $mytotem->updateDescription($description);
        /** @var Totem $totem */
        $totem = $this->totemRepository->createTotem($mytotem);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache($totem->getId(), $totem->toJson());
        }

        return $totem->toJson();
    }
}
