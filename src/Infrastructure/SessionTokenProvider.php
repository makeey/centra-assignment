<?php

namespace KanbanBoard\Infrastructure;


use Assert\Assertion;

class SessionTokenProvider implements TokenProviderInterface
{
    public function tokenStrictly(): string
    {
       Assertion::keyExists($_SESSION,'gh-token');
       Assertion::notNull($_SESSION['gh-token']);
       return $_SESSION['gh-token'];
    }
}