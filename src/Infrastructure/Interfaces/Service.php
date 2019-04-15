<?php

namespace KanbanBoard\Infrastructure\Interfaces;


interface Service
{
    public function milestones(string $account, string $repository): array;

    public function issues(string $account, string $repository, int $milestoneId): array;
}
