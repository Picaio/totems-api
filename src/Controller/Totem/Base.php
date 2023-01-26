<?php

declare(strict_types=1);

namespace App\Controller\Totem;

use App\Controller\BaseController;
use App\Service\Totem\Create;
use App\Service\Totem\Delete;
use App\Service\Totem\Find;
use App\Service\Totem\Update;

abstract class Base extends BaseController
{
    protected function getServiceFindTotem(): Find
    {
        return $this->container->get('find_totem_service');
    }

    protected function getServiceCreateTotem(): Create
    {
        return $this->container->get('create_totem_service');
    }

    protected function getServiceUpdateTotem(): Update
    {
        return $this->container->get('update_totem_service');
    }

    protected function getServiceDeleteTotem(): Delete
    {
        return $this->container->get('delete_totem_service');
    }
}
