<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\Infrastructure;

use KanbanBoard\Entities\Milestone;
use KanbanBoard\Entities\Progress;
use KanbanBoard\Infrastructure\Interfaces\MilestoneFactory as MilestoneFactoryInterface;

class MilestoneFactory implements MilestoneFactoryInterface
{
    public function milestone(array $data): Milestone
    {
        return new Milestone(
            $data['number'],
            $data['title'],
            $data['html_url'],
            $this->calculateProgress($data['closed_issues'], $data['open_issues'])
        );
    }

    private function calculateProgress(int $closed_issues, int $open_issues): Progress
    {
        return new Progress($closed_issues, $open_issues);
    }
}
