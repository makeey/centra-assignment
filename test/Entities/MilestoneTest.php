<?php


use KanbanBoard\Entities\Issue;
use KanbanBoard\Entities\IssueState;
use KanbanBoard\Entities\Milestone;
use KanbanBoard\Entities\Progress;
use PHPUnit\Framework\TestCase;

class MilestoneTest extends TestCase
{

    private $title;
    private $url;
    private $progress;
    private $number;
    private $repository;
    private $issues;
    private $activeIssue;
    private $queuedIssue;
    private $completedIssue;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->title = 'title';
        $this->url = 'http://localhost';
        $this->progress = new Progress(10, 10);
        $this->number = 2;
        $this->repository = 'repository';

       $this->activeIssue = new Issue(
            1,
            2,
            'title active issue',
            'body',
            'http://localhost',
            IssueState::ACTIVE,
            new Progress(0, 5),
            []
        );

       $this->queuedIssue = new Issue(
            2,
            3,
            'title queued issue',
            'body',
            'http://localhost',
            IssueState::QUEUED,
            new Progress(5, 3),
            []
        );

        $this->completedIssue = new Issue(
            3,
            4,
            'title completed issue',
            'body',
            'http://localhost',
            IssueState::COMPLETED,
            new Progress(10, 0),
            []
        );

        $this->issues = [$this->activeIssue, $this->queuedIssue, $this->completedIssue];

        parent::__construct($name, $data, $dataName);
    }


    public function testCanCreateAndSerializeMilestoneWithoutIssues()
    {
        $milestone = new Milestone(
            $this->number,
            $this->title,
            $this->repository,
            $this->url,
            $this->progress
        );

        $milestoneData = $milestone->jsonSerialize();

        $this->assertEquals($this->title, $milestoneData['milestone']);
        $this->assertEquals($this->url, $milestoneData['url']);
        $this->assertEquals($this->progress->jsonSerialize(), $milestoneData['progress']);
        $this->assertEquals([],$milestoneData['active']);
        $this->assertEquals([], $milestoneData['queued']);
        $this->assertEquals([], $milestoneData['active']);

    }

    public function testCanCreateAndSerializeMilestoneWithIssues()
    {
        $milestone = new Milestone(
            $this->number,
            $this->title,
            $this->repository,
            $this->url,
            $this->progress,
            ...$this->issues
        );

        $milestoneData = $milestone->jsonSerialize();

        $this->assertEquals($this->title, $milestoneData['milestone']);
        $this->assertEquals($this->url, $milestoneData['url']);
        $this->assertEquals($this->progress->jsonSerialize(), $milestoneData['progress']);
        $this->assertEquals([$this->activeIssue->jsonSerialize()],$milestoneData['active']);
        $this->assertEquals([$this->queuedIssue->jsonSerialize()], $milestoneData['queued']);
        $this->assertEquals([$this->activeIssue->jsonSerialize()], $milestoneData['active']);
    }
}