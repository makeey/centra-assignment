<?php


namespace KanbanBoard;


use KanbanBoard\Infrastructure\ApplicationInterface;
use KanbanBoard\Infrastructure\SessionTokenProvider;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class Application implements ApplicationInterface
{
    /** @var Authentication */
    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function run()
    {
        $repositories = explode('|', getenv('GH_REPOSITORIES'));
        $this->authentication->login();
        $tokenProvider = new SessionTokenProvider();
        $github = new Github($tokenProvider, getenv('GH_ACCOUNT'));
        $board = new Board($github, $repositories, array('waiting-for-feedback'));
        $data = $board->board();
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader('../views'),
        ));
        echo $m->render('index', array('milestones' => $data));
    }
}