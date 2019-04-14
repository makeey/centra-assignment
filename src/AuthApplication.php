<?php
namespace KanbanBoard;

use KanbanBoard\Infrastructure\ApplicationInterface;
use League\OAuth2\Client\Provider\AbstractProvider;

class AuthApplication implements ApplicationInterface {

    /** @var AbstractProvider */
    private $provider;

    private $application;

    public function __construct(ApplicationInterface $application, AbstractProvider $provider)
	{
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		$this->provider = $provider;
		$this->application = $application;
	}

	public function logout()
	{
		unset($_SESSION['gh-token']);
	}

	public function run(): void
    {
        if(array_key_exists('gh-token', $_SESSION)) {
            $this->application->run();
        }else {
            $this->login();
        }
    }
	private function login(): void
	{
		$token = NULL;
		if(array_key_exists('gh-token', $_SESSION)) {
			$token = $_SESSION['gh-token'];
		}
		else if(
		    !empty($_GET['state']) &&
            ($_GET['state'] === $_SESSION['oauth2state']) &&
            $_SESSION['redirected'])
		{
			$_SESSION['redirected'] = false;
			$token = $this->returnsFromGithub($_GET['code']);
		}
		else
		{
			$_SESSION['redirected'] = true;
			$this->redirectToGithub();
		}
		$this->logout();
		$_SESSION['gh-token'] = $token;
		header('');
		exit;
	}

	private function redirectToGithub()
	{
        $_SESSION['oauth2state'] = $this->provider->getState();
        header('Location: ' . $this->provider->getAuthorizationUrl());
        exit;
	}

	private function returnsFromGithub($code)
	{
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
        return $token;
	}
}
