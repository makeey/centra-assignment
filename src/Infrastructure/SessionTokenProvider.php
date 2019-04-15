<?php

namespace KanbanBoard\Infrastructure;


use Assert\Assertion;
use KanbanBoard\Infrastructure\Interfaces\TokenProvider;

class SessionTokenProvider implements TokenProvider
{
    public function tokenStrictly(): string
    {
       Assertion::keyExists($_SESSION,'gh-token');
       Assertion::notNull($_SESSION['gh-token']);
       return $_SESSION['gh-token'];
    }
}