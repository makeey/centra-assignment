<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\Infrastructure\Interfaces;

use KanbanBoard\Entities\Issue;

interface IssueFactory
{
    public function issue(array $data): Issue;
}
