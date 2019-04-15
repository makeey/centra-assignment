<?php

use KanbanBoard\AuthApplication;
use KanbanBoard\BoardApplication;
use KanbanBoard\ExternalService\Github\ClientFactory;
use KanbanBoard\ExternalService\Github\ClientFactoryInterface;
use KanbanBoard\ExternalService\Github\Github;
use KanbanBoard\Infrastructure\Interfaces\Application;
use KanbanBoard\Infrastructure\Interfaces\Board;
use KanbanBoard\Infrastructure\Interfaces\Service;
use KanbanBoard\Infrastructure\Interfaces\TokenProvider;
use KanbanBoard\Infrastructure\IssueFactory;
use KanbanBoard\Infrastructure\MilestoneFactory;
use KanbanBoard\Infrastructure\SessionTokenProvider;
use League\OAuth2\Client\Provider\AbstractProvider;
use Michelf\Markdown;
use Michelf\MarkdownInterface;
use function DI\autowire;
use function DI\get;

return [

    AbstractProvider::class => autowire(\League\OAuth2\Client\Provider\Github::class)->constructor([
        'clientId' => getenv('GH_CLIENT_ID'),
        'clientSecret' => getenv('GH_CLIENT_SECRET'),
        'redirectUri' => getenv('GH_REDIRECT_URI')
    ])->lazy(),

    MarkdownInterface::class=> autowire(Markdown::class),
    IssueFactory::class => autowire()->constructor(get(MarkdownInterface::class),explode('|', getenv('GH_PAUSE_LABELS'))),
    MilestoneFactory::class => autowire(),

    TokenProvider::class => autowire(SessionTokenProvider::class)->lazy(),

    ClientFactoryInterface::class => autowire(ClientFactory::class)->constructor(get(TokenProvider::class)),

    Service::class => autowire(Github::class)->constructor(
        get(ClientFactoryInterface::class),
        get(IssueFactory::class),
        get(MilestoneFactory::class)
    )->lazy(),

    Board::class => autowire(\KanbanBoard\ExternalService\Github\Board::class)->constructor(
        get(Service::class),
        explode('|', getenv('GH_REPOSITORIES')),
        getenv('GH_ACCOUNT')

    )->lazy(),

    Mustache_Engine::class =>autowire()->constructor(array(
        'loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/../views'),
    ))->lazy(),

    Application::class => autowire(AuthApplication::class)->constructor(
        autowire(BoardApplication::class),
        get(AbstractProvider::class)
    ),
];