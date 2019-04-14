<?php

use KanbanBoard\AuthApplication;
use KanbanBoard\BoardApplication;
use KanbanBoard\ExternalService\ClientFactory;
use KanbanBoard\ExternalService\ClientFactoryInterface;
use KanbanBoard\ExternalService\Service;
use KanbanBoard\Github;
use KanbanBoard\Infrastructure\ApplicationInterface;
use KanbanBoard\Infrastructure\Board;
use KanbanBoard\Infrastructure\IssueFactory;
use KanbanBoard\Infrastructure\MilestoneFactory;
use KanbanBoard\Infrastructure\SessionTokenProvider;
use KanbanBoard\Infrastructure\TokenProviderInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use function DI\autowire;
use function DI\get;
use Michelf\Markdown;
use Michelf\MarkdownInterface;

return [

    AbstractProvider::class => autowire(\League\OAuth2\Client\Provider\Github::class)->constructor([
        'clientId' => getenv('GH_CLIENT_ID'),
        'clientSecret' => getenv('GH_CLIENT_SECRET'),
        'redirectUri' => getenv('GH_REDIRECT_URI')
    ])->lazy(),

    MarkdownInterface::class=> autowire(Markdown::class),
    IssueFactory::class => autowire()->constructor(get(MarkdownInterface::class),explode('|', getenv('GH_PAUSE_LABELS'))),
    MilestoneFactory::class => autowire(),

    TokenProviderInterface::class => autowire(SessionTokenProvider::class)->lazy(),

    ClientFactoryInterface::class => autowire(ClientFactory::class)->constructor(get(TokenProviderInterface::class)),

    Service::class => autowire(Github::class)->constructor(
        get(ClientFactoryInterface::class),
        get(IssueFactory::class),
        get(MilestoneFactory::class)
    )->lazy(),

    Board::class => autowire(\KanbanBoard\Board::class)->constructor(
        get(Service::class),
        explode('|', getenv('GH_REPOSITORIES')),
        getenv('GH_ACCOUNT')

    )->lazy(),

    ApplicationInterface::class => autowire(AuthApplication::class)->constructor(
        autowire(BoardApplication::class),
        get(AbstractProvider::class)
    ),
];