<?php

use KanbanBoard\Application;
use KanbanBoard\Authentication;
use KanbanBoard\Infrastructure\ApplicationInterface;
use function DI\autowire;

return [
    Authentication::class => autowire()->constructor(getenv('GH_CLIENT_ID'), getenv('GH_CLIENT_SECRET')),
    ApplicationInterface::class => autowire(Application::class),
];