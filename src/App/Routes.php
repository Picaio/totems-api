<?php

declare(strict_types=1);

use App\Controller\Totem;
use App\Controller\Media;
use App\Controller\User;
use App\Middleware\Auth;

return function ($app) {
    $app->get('/', 'App\Controller\DefaultController:getHelp');
    $app->get('/status', 'App\Controller\DefaultController:getStatus');
    $app->post('/login', \App\Controller\User\Login::class);

    $app->group('/api/v1', function () use ($app): void {
        $app->group('/Media', function () use ($app): void {
            $app->get('', Media\GetAll::class);
            $app->post('', Media\Create::class);
            $app->get('/{id}', Media\GetOne::class);
            $app->put('/{id}', Media\Update::class);
            $app->delete('/{id}', Media\Delete::class);
        })->add(new Auth());

        $app->group('/users', function () use ($app): void {
            $app->get('', User\GetAll::class)->add(new Auth());
            $app->post('', User\Create::class);
            $app->get('/{id}', User\GetOne::class)->add(new Auth());
            $app->put('/{id}', User\Update::class)->add(new Auth());
            $app->delete('/{id}', User\Delete::class)->add(new Auth());
        });

        $app->group('/totems', function () use ($app): void {
            $app->get('', Totem\GetAll::class);
            $app->post('', Totem\Create::class);
            $app->get('/{id}', Totem\GetOne::class);
            $app->put('/{id}', Totem\Update::class);
            $app->delete('/{id}', Totem\Delete::class);
        });
    });

    return $app;
};
