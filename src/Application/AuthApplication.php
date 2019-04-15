<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\Application;

use KanbanBoard\Infrastructure\Interfaces\Application;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

class AuthApplication implements Application
{
    /** @var AbstractProvider */
    private $provider;
    /** @var Application */
    private $application;

    public function __construct(Application $application, AbstractProvider $provider)
    {
        if (\session_status() === PHP_SESSION_NONE) {
            \session_start();
        }
        $this->provider = $provider;
        $this->application = $application;
    }

    public function run(): void
    {
        if (isset($_SESSION['gh-token'])) {
            $this->application->run();
        } else {
            $this->login();
        }
    }

    private function login(): void
    {
       if (!isset($_GET['code'])) {
            $this->redirectToGithub();
        } elseif(
            array_key_exists('state', $_GET) && null === $_GET['state'] &&
           ($_GET['state'] !== $_SESSION['oauth2state'])
        ) {
           throw new \InvalidArgumentException('Invalid oauth state');
        }
        $token = $this->returnsFromGithub($_GET['code']);
        $_SESSION['gh-token'] = $token->getToken();
        $this->run();
    }

    private function returnsFromGithub(string $code): AccessToken
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        return $token;
    }

    private function redirectToGithub(): void
    {
        $_SESSION['oauth2state'] = $this->provider->getState();
        \header('Location: ' . $this->provider->getAuthorizationUrl());
        exit;
    }

    public function logout(): void
    {
        unset($_SESSION['gh-token']);
    }
}
