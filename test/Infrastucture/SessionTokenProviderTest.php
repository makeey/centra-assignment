<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

use KanbanBoard\Infrastructure\SessionTokenProvider;
use PHPUnit\Framework\TestCase;

class SessionTokenProviderTest extends TestCase
{
    private $token = 'token';

    public function testThrowExceptionWithoutSessionKey()
    {
        $tokenProvider = new SessionTokenProvider();
        $this->expectException(\Assert\InvalidArgumentException::class);
        $tokenProvider->tokenStrictly();
    }

    public function testThrowExceptionWithoutSessionValue()
    {
        $_SESSION['gh-token'] = null;
        $tokenProvider = new SessionTokenProvider();
        $this->expectException(\Assert\InvalidArgumentException::class);
        $tokenProvider->tokenStrictly();
    }

    public function testCanGetTokenFromSession()
    {
        $_SESSION['gh-token'] = $this->token;
        $tokenProvider = new SessionTokenProvider();
        $this->assertEquals($this->token, $tokenProvider->tokenStrictly());
    }
}
