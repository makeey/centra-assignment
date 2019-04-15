<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use KanbanBoard\Entities\Progress;
use KanbanBoard\Infrastructure\MilestoneFactory;
use PHPUnit\Framework\TestCase;

class MilestoneFactoryTest extends TestCase
{
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

    public function testCanCreateMilestone()
    {
        $factory = new MilestoneFactory();
        $milestone = $factory->milestone($this->milestoneData);
        $this->assertEquals($this->milestoneData['title'], $milestone->title());
        $this->assertEquals($this->milestoneData['html_url'], $milestone->url());
        $this->assertEquals($this->milestoneData['number'], $milestone->number());
        $this->assertInstanceOf(Progress::class, $milestone->progress());
        $this->assertEquals($this->milestoneData['open_issues'], $milestone->progress()->remaining());
        $this->assertEquals($this->milestoneData['closed_issues'], $milestone->progress()->complete());
        $this->assertEquals(
            $this->milestoneData['open_issues'] + $this->milestoneData['closed_issues'],
            $milestone->progress()->total()
        );
        $this->assertEqualsWithDelta(0, $milestone->progress()->percent(), 0.0001);
    }
}
