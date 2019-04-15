<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$builder = new DI\ContainerBuilder();
$builder->useAutowiring(true);
$builder->addDefinitions(require_once __DIR__. '/config/di.php');
$container = $builder->build();

return $container;
