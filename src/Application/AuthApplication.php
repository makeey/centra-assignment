<?php

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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->provider = $provider;
        $this->application = $application;
    }

    public function run(): void
    {
        if (array_key_exists('gh-token', $_SESSION)) {
            $this->application->run();
        } else {
            $this->login();
        }
    }

    private function login(): void
    {
        $token = NULL;
        if (array_key_exists('gh-token', $_SESSION)) {
            $token = $_SESSION['gh-token'];
        } else if (
            null !== $_GET['code'] &&
            ($_GET['state'] === $_SESSION['oauth2state']) &&
            $_SESSION['redirected']) {
            $_SESSION['redirected'] = false;
            $token = $this->returnsFromGithub($_GET['code']);
        } else {
            $_SESSION['redirected'] = true;
            $this->redirectToGithub();
        }
        $this->logout();
        $_SESSION['gh-token'] = $token;
        header('');
        exit;
    }

    private function returnsFromGithub(string $code): AccessToken
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
        return $token;
    }

    private function redirectToGithub(): void
    {
        $_SESSION['oauth2state'] = $this->provider->getState();
        header('Location: ' . $this->provider->getAuthorizationUrl());
        exit;
    }

    public function logout(): void
    {
        unset($_SESSION['gh-token']);
    }
}
