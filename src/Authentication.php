<?php
namespace KanbanBoard;

use League\OAuth2\Client\Provider\AbstractProvider;

class Authentication {

    /** @var AbstractProvider */
    private $provider;

    public function __construct(AbstractProvider $provider)
	{
		$this->provider = $provider;
	}

	public function logout()
	{
		unset($_SESSION['gh-token']);
	}

	public function login()
	{
		session_start();
		$token = NULL;
		if(array_key_exists('gh-token', $_SESSION)) {
			$token = $_SESSION['gh-token'];
		}
		else if(Utilities::hasValue($_GET, 'code')
			&& Utilities::hasValue($_GET, 'state')
			&& $_SESSION['redirected'])
		{
			$_SESSION['redirected'] = false;
			$token = $this->_returnsFromGithub($_GET['code']);
		}
		else
		{
			$_SESSION['redirected'] = true;
			$this->_redirectToGithub();
		}
		$this->logout();
		$_SESSION['gh-token'] = $token;
		return $token;
	}

	private function _redirectToGithub()
	{
        $authUrl = $this->provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $this->provider->getState();
        header('Location: '.$authUrl);
        exit;
	}

	private function _returnsFromGithub($code)
	{
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
        return $token;
	}
}
