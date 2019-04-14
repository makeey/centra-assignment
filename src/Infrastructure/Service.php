<?php

namespace KanbanBoard\Infrastructure;


interface Service
{
    public function milestones(string $account, string $repository): array;

    public function issues(string $account, string $repository, int $milestoneId): array;
}
