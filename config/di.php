<?php

use KanbanBoard\BoardApplication;
use KanbanBoard\AuthApplication;
use KanbanBoard\Github;
use KanbanBoard\Infrastructure\ApplicationInterface;
use KanbanBoard\Infrastructure\Board;
use KanbanBoard\Infrastructure\SessionTokenProvider;
use KanbanBoard\Infrastructure\TokenProviderInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use function DI\autowire;
use function DI\get;

return [
    AbstractProvider::class => autowire(\League\OAuth2\Client\Provider\Github::class)->constructor([
            'clientId' => getenv('GH_CLIENT_ID'),
            'clientSecret' => getenv('GH_CLIENT_SECRET'),
            'redirectUri' => getenv('GH_REDIRECT_URI')
        ])->lazy(),
//    Authentication::class => autowire()->constructor(get(AbstractProvider::class))->lazy(),
    TokenProviderInterface::class => autowire(SessionTokenProvider::class)->lazy(),
    Github::class => autowire()->constructor(get(TokenProviderInterface::class), getenv('GH_ACCOUNT'))->lazy(),
    Board::class => autowire(\KanbanBoard\Board::class)->constructor(get(Github::class), explode('|', getenv('GH_REPOSITORIES')), explode('|', getenv('GH_PAUSE_LABELS')))->lazy(),
    ApplicationInterface::class => autowire(AuthApplication::class)->constructor(
        autowire(BoardApplication::class),
        get(AbstractProvider::class)
    ),
];