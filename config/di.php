<?php

use KanbanBoard\Application;
use KanbanBoard\Infrastructure\ApplicationInterface;
use function DI\autowire;

return [
    ApplicationInterface::class => autowire(Application::class),
];