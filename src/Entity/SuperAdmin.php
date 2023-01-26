<?php

declare(strict_types=1);

namespace App\Entity;

final class SuperAdmin extends User
{

    public function toJson(): object
    {
        return json_decode((string) json_encode(get_object_vars($this)), false);
    }

    
}
