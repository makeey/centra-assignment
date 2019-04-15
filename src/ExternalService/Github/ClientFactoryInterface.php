<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\ExternalService\Github;

use Github\Api\Issue;

interface ClientFactoryInterface
{
    public function issueClient(): Issue;

    public function milestoneClient(): Issue\Milestones;
}
