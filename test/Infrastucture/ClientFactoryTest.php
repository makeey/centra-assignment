<?php


use Github\Api\Issue;
use KanbanBoard\ExternalService\ClientFactory;
use KanbanBoard\Infrastructure\TokenProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ClientFactoryTest extends TestCase
{
    /** @var MockObject| TokenProviderInterface */
    private $tokenProvider;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->tokenProvider = $this->createMock(TokenProviderInterface::class);
        parent::__construct($name, $data, $dataName);
    }

    public function testCanCreateIssueClient()
    {
        $_SESSION['gh-token'] = null;
        $factory = new ClientFactory($this->tokenProvider);
        $this->assertInstanceOf(Issue::class, $factory->issueClient());
    }

    public function testCanCreateMilestoneClient()
    {
        $factory = new ClientFactory($this->tokenProvider);
        $this->assertInstanceOf(Issue\Milestones::class, $factory->milestoneClient());
    }
}