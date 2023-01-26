<?php

declare(strict_types=1);

namespace App\Controller\Media;

use Slim\Http\Request;
use Slim\Http\Response;

final class Update extends Base
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
        $media = $this->getMediaService()->update($input, (int) $args['id']);

        return $this->jsonResponse($response, 'success', $media, 200);
    }
}
