<?php

namespace KanbanBoard\Infrastructure\Interfaces;


use KanbanBoard\Entities\Issue;

interface IssueFactory
{
    public function issue(array $data): Issue;
}