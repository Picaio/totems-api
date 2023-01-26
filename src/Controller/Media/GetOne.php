<?php

declare(strict_types=1);

namespace App\Controller\Media;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetOne extends Base
{
    /**
     * @param array<string> $args
     */
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $input = (array) $request->getParsedBody();
        $mediaId = (int) $args['id'];
        $userId = $this->getAndValidateUserId($input);
        $media = $this->getMediaService()->getOne($mediaId, $userId);

        return $this->jsonResponse($response, 'success', $media, 200);
    }
}
