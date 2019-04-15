<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\Entities;

class IssueState
{
    public const COMPLETED = 'completed';
    public const ACTIVE = 'active';
    public const QUEUED = 'queued';
}
