<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\Infrastructure;

use Assert\Assertion;
use KanbanBoard\Infrastructure\Interfaces\TokenProvider;

class SessionTokenProvider implements TokenProvider
{
    public function tokenStrictly(): string
    {
        Assertion::keyExists($_SESSION, 'gh-token');
        Assertion::notNull($_SESSION['gh-token']);

        return $_SESSION['gh-token'];
    }
}
