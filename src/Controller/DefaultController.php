<?php

declare(strict_types=1);

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

final class DefaultController extends BaseController
{
    private const API_VERSION = '1.0';

    public function getHelp(Request $request, Response $response): Response
    {
        date_default_timezone_set("America/New_York");
        $url = $this->container->get('settings')['app']['domain'];
        $endpoints = [
            'login' => $url . '/login',
            'tasks' => $url . '/api/v1/tasks',
            'users' => $url . '/api/v1/users',
            'totems' => $url . '/api/v1/totems',
            'docs' => $url . '/docs/index.html',
            'status' => $url . '/status',
            'this help' => $url . '',
        ];
        $message = [
            'endpoints' => $endpoints,
            'version' => self::API_VERSION,
            'timestamp' => time(),
        ];

        return $this->jsonResponse($response, 'success', $message, 200);
    }

    public function getStatus(Request $request, Response $response): Response
    {
        $status = [
            'stats' => $this->getDbStats(),
            'MySQL' => 'OK',
            'Redis' => $this->checkRedisConnection(),
            'version' => self::API_VERSION,
            'timestamp' => time(),
        ];

        return $this->jsonResponse($response, 'success', $status, 200);
    }

    /**
     * @return array<int>
     */
    private function getDbStats(): array
    {
        $taskService = $this->container->get('task_service');
        $userService = $this->container->get('find_user_service');
        $totemService = $this->container->get('find_totem_service');

        return [
            'tasks' => count($taskService->getAllTasks()),
            'users' => count($userService->getAll()),
            'totems' => count($totemService->getAll()),
        ];
    }

    private function checkRedisConnection(): string
    {
        $redis = 'Disabled';
        if (self::isRedisEnabled() === true) {
            $redisService = $this->container->get('redis_service');
            $key = $redisService->generateKey('test:status');
            $redisService->set($key, new \stdClass());
            $redis = 'OK';
        }

        return $redis;
    }
}
