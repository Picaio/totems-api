<?php

declare(strict_types=1);

namespace App\Entity;

final class Client extends User
{
    private string $nit;

    public function toJson(): object
    {
        return json_decode((string) json_encode(get_object_vars($this)), false);
    }

    public function getNit(): string
    {
        return $this->nit;
    }

    public function updateNit(string $nit): self
    {
        $this->nit = $nit;

        return $this;
    }
}
