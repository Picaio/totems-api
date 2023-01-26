<?php

declare(strict_types=1);

namespace App\Controller\Media;

use App\Controller\BaseController;
use App\Exception\Media;
use App\Service\Media\MediaService;

abstract class Base extends BaseController
{
    protected function getMediaService(): MediaService
    {
        return $this->container->get('media_service');
    }

    protected function getAndValidateUserId(array $input): int
    {
        if (isset($input['decoded']) && isset($input['decoded']->sub)) {
            return (int) $input['decoded']->sub;
        }

        throw new Media('Invalid user. Permission failed.', 400);
    }
}
