<?php

declare(strict_types=1);

use App\Service\Totem;
use App\Service\Media\MediaService;
use App\Service\User;
use Psr\Container\ContainerInterface;

$container['find_user_service'] = static fn (
    ContainerInterface $container
): User\Find => new User\Find(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['create_user_service'] = static fn (
    ContainerInterface $container
): User\Create => new User\Create(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['update_user_service'] = static fn (
    ContainerInterface $container
): User\Update => new User\Update(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['delete_user_service'] = static fn (
    ContainerInterface $container
): User\Delete => new User\Delete(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['login_user_service'] = static fn (
    ContainerInterface $container
): User\Login => new User\Login(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['media_service'] = static fn (
    ContainerInterface $container
): MediaService => new MediaService(
    $container->get('media_repository'),
    $container->get('redis_service')
);

$container['find_totem_service'] = static fn (
    ContainerInterface $container
): Totem\Find => new Totem\Find(
    $container->get('totem_repository'),
    $container->get('redis_service')
);

$container['create_totem_service'] = static fn (
    ContainerInterface $container
): Totem\Create => new Totem\Create(
    $container->get('totem_repository'),
    $container->get('redis_service')
);

$container['update_totem_service'] = static fn (
    ContainerInterface $container
): Totem\Update => new Totem\Update(
    $container->get('totem_repository'),
    $container->get('redis_service')
);

$container['delete_totem_service'] = static fn (
    ContainerInterface $container
): Totem\Delete => new Totem\Delete(
    $container->get('totem_repository'),
    $container->get('redis_service')
);
