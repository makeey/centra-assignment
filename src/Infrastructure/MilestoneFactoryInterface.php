<?php

namespace KanbanBoard\Infrastructure;

use KanbanBoard\Entities\Milestone;

interface MilestoneFactoryInterface
{
    public function milestone(array $data): Milestone;
}