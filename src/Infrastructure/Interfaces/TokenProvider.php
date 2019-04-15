<?php

namespace KanbanBoard\Infrastructure\Interfaces;

interface TokenProvider
{
    public function tokenStrictly(): string;
}