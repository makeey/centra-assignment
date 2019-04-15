<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use Cache\Adapter\Filesystem\FilesystemCachePool;
use function DI\autowire;
use function DI\get;
use KanbanBoard\Application\AuthApplication;
use KanbanBoard\Application\BoardApplication;
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
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use Michelf\Markdown;
use Michelf\MarkdownInterface;
use Psr\Cache\CacheItemPoolInterface;

return [

    FilesystemInterface::class => function () {
        return new Filesystem(new Local(__DIR__.'/../'));
    },

    CacheItemPoolInterface::class => function (FilesystemInterface $filesystem) {
        $pool = new FilesystemCachePool($filesystem);
        $pool->setFolder('tmp/git-cache');

        return $pool;
    },

    AbstractProvider::class => autowire(\League\OAuth2\Client\Provider\Github::class)->constructor([
        'clientId' => \getenv('GH_CLIENT_ID'),
        'clientSecret' => \getenv('GH_CLIENT_SECRET'),
        'redirectUri' => \getenv('GH_REDIRECT_URI'),
    ])->lazy(),

    MarkdownInterface::class => autowire(Markdown::class),
    IssueFactory::class => autowire()->constructor(get(MarkdownInterface::class), \explode('|', \getenv('GH_PAUSE_LABELS'))),
    MilestoneFactory::class => autowire(),

    TokenProvider::class => autowire(SessionTokenProvider::class)->lazy(),

    ClientFactoryInterface::class => autowire(ClientFactory::class)->constructor(
        get(TokenProvider::class),
        get(CacheItemPoolInterface::class)
    ),

    Service::class => autowire(Github::class)->constructor(
        get(ClientFactoryInterface::class),
        get(IssueFactory::class),
        get(MilestoneFactory::class)
    )->lazy(),

    Board::class => autowire(\KanbanBoard\ExternalService\Github\Board::class)->constructor(
        get(Service::class),
        \explode('|', \getenv('GH_REPOSITORIES')),
        \getenv('GH_ACCOUNT')

    )->lazy(),

    Mustache_Engine::class => autowire()->constructor([
        'loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/../views'),
    ])->lazy(),

    Application::class => autowire(AuthApplication::class)->constructor(
        autowire(BoardApplication::class),
        get(AbstractProvider::class)
    )->lazy(),
];
