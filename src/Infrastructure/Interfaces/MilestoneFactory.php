<?php

namespace KanbanBoard\Infrastructure\Interfaces;

use KanbanBoard\Entities\Milestone;

interface MilestoneFactory
{
    public function milestone(array $data): Milestone;
}