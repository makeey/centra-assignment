<?php

use KanbanBoard\Entities\IssueState;
use KanbanBoard\Entities\Progress;
use KanbanBoard\Infrastructure\IssueFactory;
use Michelf\Markdown;
use PHPUnit\Framework\TestCase;

class IssueFactoryTest extends TestCase
{
    private $issueData = [
        'id' => 1,
        'html_url' => 'https://github.com/octocat/Hello-World/issues/1347',
        'number' => 1347,
        'state' => 'open',
        'title' => 'Found a bug',
        'body' => 'I\'m having a problem with this.',
        'labels' =>
            [
                0 =>
                    [
                        'id' => 208045946,
                        'node_id' => 'MDU6TGFiZWwyMDgwNDU5NDY=',
                        'url' => 'https://api.github.com/repos/octocat/Hello-World/labels/bug',
                        'name' => 'bug',
                        'description' => 'Something isn\'t working',
                        'color' => 'f29513',
                        'default' => true,
                    ],
            ],
        'assignee' =>
            [
                'login' => 'octocat',
                'id' => 1,
                'node_id' => 'MDQ6VXNlcjE=',
                'avatar_url' => 'https://github.com/images/error/octocat_happy.gif',
                'gravatar_id' => '',
                'url' => 'https://api.github.com/users/octocat',
                'html_url' => 'https://github.com/octocat',
                'followers_url' => 'https://api.github.com/users/octocat/followers',
                'following_url' => 'https://api.github.com/users/octocat/following{/other_user}',
                'gists_url' => 'https://api.github.com/users/octocat/gists{/gist_id}',
                'starred_url' => 'https://api.github.com/users/octocat/starred{/owner}{/repo}',
                'subscriptions_url' => 'https://api.github.com/users/octocat/subscriptions',
                'organizations_url' => 'https://api.github.com/users/octocat/orgs',
                'repos_url' => 'https://api.github.com/users/octocat/repos',
                'events_url' => 'https://api.github.com/users/octocat/events{/privacy}',
                'received_events_url' => 'https://api.github.com/users/octocat/received_events',
                'type' => 'User',
                'site_admin' => false,
            ],
        'active_lock_reason' => 'too heated',
        'pull_request' =>
            [
                'url' => 'https://api.github.com/repos/octocat/Hello-World/pulls/1347',
                'html_url' => 'https://github.com/octocat/Hello-World/pull/1347',
                'diff_url' => 'https://github.com/octocat/Hello-World/pull/1347.diff',
                'patch_url' => 'https://github.com/octocat/Hello-World/pull/1347.patch',
            ],
        'closed_at' => NULL,
        'created_at' => '2011-04-22T13:33:48Z',
        'updated_at' => '2011-04-22T13:33:48Z',
    ];
    private $issueData2 = [
        'id' => 1,
        'html_url' => 'https://github.com/octocat/Hello-World/issues/1347',
        'number' => 1348,
        'state' => 'closed',
        'title' => 'Found a bug',
        'body' => 'I\'m having a problem with this.  [x] [x] [ ] [ ] [ ] [ ]',
        'labels' =>
            [

                0 =>
                    [
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

    private $markdown;
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->markdown = new Markdown();
        parent::__construct($name, $data, $dataName);
    }

    public function testCanCreateIssueWithAssigned()
    {
        $factory = new IssueFactory($this->markdown,['waiting-for-feedback']);

        $issue = $factory->issue($this->issueData);

        $this->assertEquals($this->issueData['id'], $issue->id());
        $this->assertEquals($this->issueData['number'], $issue->number());
        $this->assertEquals($this->issueData['title'], $issue->title());
        $this->assertEquals($this->markdown->transform($this->issueData['body']), $issue->body() );
        $this->assertEquals($this->issueData['html_url'], $issue->url());
        $this->assertEquals($this->issueData['assignee']['avatar_url'].'?s=16', $issue->assignee());
        $this->assertEquals(IssueState::ACTIVE,$issue->state());
        $this->assertEquals($this->issueData['closed_at'], $issue->closed());
        $this->assertEmpty($issue->paused());

        $this->assertInstanceOf(Progress::class,$issue->progress());
        $this->assertEquals(0, $issue->progress()->percent());
        $this->assertEquals(0, $issue->progress()->complete());
        $this->assertEquals(0, $issue->progress()->total());
        $this->assertEquals(0, $issue->progress()->remaining());

    }

    public function testCanCreateIssueWithOutAssigned()
    {
        $factory = new IssueFactory($this->markdown,['waiting-for-feedback']);

        $issue = $factory->issue($this->issueData2);

        $this->assertEquals($this->issueData2['id'], $issue->id());
        $this->assertEquals($this->issueData2['number'], $issue->number());
        $this->assertEquals($this->issueData2['title'], $issue->title());
        $this->assertEquals($this->markdown->transform($this->issueData2['body']), $issue->body() );
        $this->assertEquals($this->issueData2['html_url'], $issue->url());
        $this->assertEquals(IssueState::COMPLETED, $issue->state());
        $this->assertEquals($this->issueData2['closed_at'], $issue->closed());
        $this->assertEmpty($issue->paused());
        $this->assertNull($issue->assignee());

        $this->assertInstanceOf(Progress::class,$issue->progress());
        $this->assertEquals(33.0, $issue->progress()->percent());
        $this->assertEquals(2, $issue->progress()->complete());
        $this->assertEquals(6, $issue->progress()->total());
        $this->assertEquals(4, $issue->progress()->remaining());

    }
}