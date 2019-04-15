<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use KanbanBoard\Entities\Issue;
use KanbanBoard\Entities\IssueState;
use KanbanBoard\Entities\Milestone;
use KanbanBoard\Entities\Progress;
use KanbanBoard\ExternalService\Github\Board;
use KanbanBoard\Infrastructure\Interfaces\Service;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    private $service;
    private $account;
    private $repository;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->service = $this->createMock(Service::class);
        $this->account = 'account';
        $this->repository = ['repository', 'second_repository'];
        parent::__construct($name, $data, $dataName);
    }

    public function testBoard()
    {
        $milestone = new Milestone(
            1,
            'title 1',
            'url',
            new Progress(7, 5)
        );

        $milestone1 = new Milestone(
            2,
            'title 2',
            'url',
            new Progress(6, 5)
        );

        $issue = new Issue(
            1,
            2,
            'title',
            'body',
            'url',
            IssueState::ACTIVE,
            new Progress(4, 5),
            []
        );
        $issue2 = new Issue(
            2,
            3,
            'title',
            'body',
            'url',
            IssueState::ACTIVE,
            new Progress(4, 5),
            []
        );
        $this->service->expects($this->exactly(2))->method('milestones')
            ->withConsecutive([$this->account, $this->repository[0]], [$this->account, $this->repository[1]])
            ->willReturnOnConsecutiveCalls([$milestone], [$milestone1]);

        $this->service->expects($this->exactly(2))->method('issues')
            ->withConsecutive(
                [$this->account, $this->repository[0], $milestone->number()],
                [$this->account, $this->repository[1], $milestone1->number()]
            )
            ->willReturnOnConsecutiveCalls(
                [$issue],
                [$issue2]
            );

        $board = new Board($this->service, $this->repository, $this->account);

        $data = $board->board();

        $this->assertEquals($issue->jsonSerialize(), $data[0]['active'][0]);
        $this->assertEquals($issue2->jsonSerialize(), $data[1]['active'][0]);
    }
}
