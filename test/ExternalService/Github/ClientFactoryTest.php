<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use Github\Api\Issue;

use KanbanBoard\ExternalService\Github\ClientFactory;
use KanbanBoard\Infrastructure\Interfaces\TokenProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;

class ClientFactoryTest extends TestCase
{
    /** @var MockObject| TokenProvider */
    private $tokenProvider;
    /** @var MockObject| CacheItemPoolInterface */
    private $cachePool;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->tokenProvider = $this->createMock(TokenProvider::class);
        $this->cachePool = $this->createMock(CacheItemPoolInterface::class);
        parent::__construct($name, $data, $dataName);
    }

    public function testCanCreateIssueClient()
    {
        $_SESSION['gh-token'] = null;
        $factory = new ClientFactory($this->tokenProvider, $this->cachePool);
        $this->assertInstanceOf(Issue::class, $factory->issueClient());
    }

    public function testCanCreateMilestoneClient()
    {
        $factory = new ClientFactory($this->tokenProvider, $this->cachePool);
        $this->assertInstanceOf(Issue\Milestones::class, $factory->milestoneClient());
    }
}
