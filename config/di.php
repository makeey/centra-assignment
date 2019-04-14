<?php

use KanbanBoard\AuthApplication;
use KanbanBoard\BoardApplication;
use KanbanBoard\ExternalService\ClientFactory;
use KanbanBoard\ExternalService\ClientFactoryInterface;
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

    TokenProviderInterface::class => autowire(SessionTokenProvider::class)->lazy(),

    ClientFactoryInterface::class => autowire(ClientFactory::class)->constructor(get(TokenProviderInterface::class)),

    Github::class => autowire()->constructor(
        get(ClientFactoryInterface::class),
        getenv('GH_ACCOUNT')
    )->lazy(),

    Board::class => autowire(\KanbanBoard\Board::class)->constructor(
        get(Github::class),
        explode('|', getenv('GH_REPOSITORIES')),
        explode('|', getenv('GH_PAUSE_LABELS'))
    )->lazy(),

    ApplicationInterface::class => autowire(AuthApplication::class)->constructor(
        autowire(BoardApplication::class),
        get(AbstractProvider::class)
    ),
];