<?php

namespace KanbanBoard\Infrastructure;

interface TokenProviderInterface
{
    public function tokenStrictly(): string;
}