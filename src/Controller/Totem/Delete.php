<?php

declare(strict_types=1);

namespace App\Controller\Totem;

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
        $this->getServiceDeleteTotem()->delete((int) $args['id']);

        return $this->jsonResponse($response, 'success', null, 204);
    }
}
