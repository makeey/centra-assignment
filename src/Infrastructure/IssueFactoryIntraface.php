<?php

namespace KanbanBoard\Infrastructure;


use KanbanBoard\Entities\Issue;

interface IssueFactoryIntraface
{
    public function issue(array $data): Issue;
}