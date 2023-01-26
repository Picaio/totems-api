<?php

declare(strict_types=1);

use App\Repository\TotemRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;

$container['user_repository'] = static fn (ContainerInterface $container): UserRepository => new UserRepository($container->get('db'));

$container['media_repository'] = static fn (ContainerInterface $container): MediaRepository => new MediaRepository($container->get('db'));

$container['totem_repository'] = static fn (ContainerInterface $container): TotemRepository => new TotemRepository($container->get('db'));
