<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use KanbanBoard\Entities\Issue;
use KanbanBoard\Entities\IssueState;
use KanbanBoard\Entities\Progress;
use PHPUnit\Framework\TestCase;

class IssueTest extends TestCase
{
    /** @var int */
    private $id;
    /** @var int */
    private $number;
    /** @var string */
    private $title;
    /** @var string */
    private $body;
    /** @var string */
    private $url;
    /** @var string */
    private $state;
    /** @var Progress */
    private $progress;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->id = 1;
        $this->number = 2;
        $this->title = 'title';
        $this->body = 'body';
        $this->url = 'http://localhost/';
        $this->state = IssueState::ACTIVE;
        $this->progress = new Progress(10, 10);

        parent::__construct($name, $data, $dataName);
    }

    public function testCanJsonSerialise()
    {
        $issue = new Issue(
            $this->id,
            $this->number,
            $this->title,
            $this->body,
            $this->url,
            $this->state,
            $this->progress,
            [],
            null,
            null,
            ['pull_request']
        );

        $issueArray = $issue->jsonSerialize();

        $this->assertEquals($this->id, $issueArray['id']);
        $this->assertEquals($this->number, $issueArray['number']);
        $this->assertEquals($this->title, $issueArray['title']);
        $this->assertEquals($this->body, $issueArray['body']);
        $this->assertEquals($this->url, $issueArray['url']);
        $this->assertEquals($this->state, $issueArray['state']);
        $this->assertEquals([], $issueArray['paused']);
        $this->assertEquals($this->progress->jsonSerialize(), $issueArray['progress']);
        $this->assertTrue($issue->isHasPullRequest());
    }
}
