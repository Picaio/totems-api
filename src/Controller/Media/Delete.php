<?php

declare(strict_types=1);

namespace App\Controller\Media;

use Slim\Http\Request;
use Slim\Http\Response;

final class Delete extends Base
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
        $this->getMediaService()->delete($mediaId, $userId);

        return $this->jsonResponse($response, 'success', null, 204);
    }
}
