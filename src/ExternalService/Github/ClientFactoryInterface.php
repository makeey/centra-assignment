<?php

namespace KanbanBoard\ExternalService\Github;


use Github\Api\Issue;

interface ClientFactoryInterface
{
    public function issueClient(): Issue;

    public function milestoneClient(): Issue\Milestones;
}