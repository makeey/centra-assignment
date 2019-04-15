<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use KanbanBoard\Infrastructure\Interfaces\Application;

$container = require_once '../bootstrap.php';

$container->call(static function (Application $application) {
    $application->run();
});
