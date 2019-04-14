<?php

use function DI\get;
use KanbanBoard\Application;
use KanbanBoard\Authentication;
use KanbanBoard\Github;
use KanbanBoard\Infrastructure\ApplicationInterface;
use KanbanBoard\Infrastructure\Board;
use KanbanBoard\Infrastructure\SessionTokenProvider;
use KanbanBoard\Infrastructure\TokenProviderInterface;
use function DI\autowire;

return [
    Authentication::class => autowire()->constructor(getenv('GH_CLIENT_ID'), getenv('GH_CLIENT_SECRET'))->lazy(),
    TokenProviderInterface::class => autowire(SessionTokenProvider::class)->lazy(),
    Github::class => autowire()->constructor(get(TokenProviderInterface::class), getenv('GH_ACCOUNT'))->lazy(),
    Board::class => autowire(\KanbanBoard\Board::class)->constructor(get(Github::class), explode('|', getenv('GH_REPOSITORIES')), explode('|', getenv('GH_PAUSE_LABELS')))->lazy(),
    ApplicationInterface::class => autowire(Application::class),
];