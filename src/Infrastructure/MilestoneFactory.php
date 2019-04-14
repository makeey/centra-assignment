<?php

namespace KanbanBoard\Infrastructure;

use KanbanBoard\Entities\Milestone;
use KanbanBoard\Entities\Progress;

class MilestoneFactory implements MilestoneFactoryInterface
{

    public function milestone(array $data): Milestone
    {
        return new Milestone(
            $data['number'],
            $data['title'],
            $data['repository'],
            $data['html_url'],
            $this->calculateProgress($data['closed_issues'], $data['open_issues'])
        );
    }

    private function calculateProgress($closed_issues, $open_issues): Progress
    {
        return new Progress($closed_issues, $open_issues);
    }

}