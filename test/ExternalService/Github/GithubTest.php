<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use Github\Api\Issue as IssueClient;
use Github\Api\Issue\Milestones;
use KanbanBoard\Entities\Issue;
use KanbanBoard\Entities\IssueState;
use KanbanBoard\Entities\Milestone;
use KanbanBoard\Entities\Progress;
use KanbanBoard\ExternalService\Github\ClientFactory;
use KanbanBoard\ExternalService\Github\Github;
use KanbanBoard\Infrastructure\IssueFactory;
use KanbanBoard\Infrastructure\MilestoneFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GithubTest extends TestCase
{
    /**
     * @var MockObject| ClientFactory
     */
    private $clientFactory;
    /**
     * @var MockObject| IssueFactory
     */
    private $issueFactory;
    /**
     * @var MockObject| MilestoneFactory
     */
    private $milestoneFactory;

    private $milestoneData = [
        'url' => 'https://api.github.com/repos/golang/go/milestones/98',
        'html_url' => 'https://github.com/golang/go/milestone/98',
        'labels_url' => 'https://api.github.com/repos/golang/go/milestones/98/labels',
        'id' => 4211340,
        'node_id' => 'MDk6TWlsZXN0b25lNDIxMTM0MA==',
        'number' => 98,
        'title' => 'Go1.12.4',
        'open_issues' => 7,
        'closed_issues' => 0,
        'state' => 'open',
        'created_at' => '2019-04-08T19:47:28Z',
        'updated_at' => '2019-04-11T01:22:13Z',
        'due_on' => null,
        'closed_at' => null,
        'repository' => 'go',
    ];
    private $issueData = [
              'id' => 1,
              'html_url' => 'https://github.com/octocat/Hello-World/issues/1347',
              'number' => 1348,
              'state' => 'closed',
              'title' => 'Found a bug',
              'body' => 'I\'m having a problem with this.  [x] [x] [ ] [ ] [ ] [ ]',
              'labels' => [

                      0 => [
                              'id' => 208045946,
                              'node_id' => 'MDU6TGFiZWwyMDgwNDU5NDY=',
                              'url' => 'https://api.github.com/repos/octocat/Hello-World/labels/bug',
                              'name' => 'pause',
                              'description' => 'Something isn\'t working',
                              'color' => 'f29513',
                              'default' => true,
                          ],
                  ],
              'locked' => true,
              'active_lock_reason' => 'too heated',
              'comments' => 0,
              'pull_request' => [],
              'closed_at' => '2011-04-22T13:33:48Z',
              'created_at' => '2011-04-22T13:33:48Z',
              'updated_at' => '2011-04-22T13:33:48Z',
          ];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->clientFactory = $this->createMock(ClientFactory::class);
        $this->issueFactory = $this->createMock(IssueFactory::class);
        $this->milestoneFactory = $this->createMock(MilestoneFactory::class);
        parent::__construct($name, $data, $dataName);
    }

    public function testCanCreateMilestone()
    {
        $milestoneClient = $this->createMock(Milestones::class);

        $this->clientFactory->expects($this->exactly(1))
            ->method('milestoneClient')->willReturn($milestoneClient);

        $milestoneClient->expects($this->exactly(1))->method('all')->with('account', 'repository')
            ->willReturn([
                    $this->milestoneData,
                ]
            );

        $milestone = new Milestone(98,
            'Go1.12.4',
            'https://github.com/golang/go/milestone/98',
            new Progress(7, 0));

        $this->milestoneFactory->expects($this->exactly(1))->method('milestone')->with($this->milestoneData)
            ->willReturn($milestone);

        $github = new Github($this->clientFactory, $this->issueFactory, $this->milestoneFactory);
        $this->assertEquals([$milestone], $github->milestones('account', 'repository'));
    }

    public function testCanGetIssues()
    {
        $issueClient = $this->createMock(IssueClient::class);

        $this->clientFactory->expects($this->exactly(1))
            ->method('issueClient')->willReturn($issueClient);

        $issue = new Issue(1, 2, 'title', 'body', 'url', IssueState::ACTIVE, new Progress(5, 0), []);

        $this->issueFactory->expects($this->once())->method('issue')->willReturn($issue);

        $issueClient->expects($this->once())->method('all')->willReturn([$this->issueData]);

        $github = new Github($this->clientFactory, $this->issueFactory, $this->milestoneFactory);

        $this->assertEquals([$issue], $github->issues('account', 'repository', 2));
    }
}
