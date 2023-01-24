<?php

declare(strict_types=1);

namespace App\Controller\Totem;

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
        $id = (int) $args['id'];
        $totem = $this->getServiceUpdateTotem()->update($input, $id);

        return $this->jsonResponse($response, 'success', $totem, 200);
    }
}
