<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use Dotenv\Dotenv;


require_once __DIR__ . '/vendor/autoload.php';

if (! isset($GLOBALS['container'])) {
    if (\file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv::create(__DIR__, '.env');
        $dotenv->load();
    }
    $builder = new DI\ContainerBuilder();
    $builder->useAutowiring(true);
    $builder->addDefinitions(require_once __DIR__ . '/config/di.php');
    $GLOBALS['container'] = $builder->build();
}
return $GLOBALS['container'];
