<?php


namespace KanbanBoard;


use KanbanBoard\Infrastructure\ApplicationInterface;
use KanbanBoard\Infrastructure\Board as BoardInterface;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class Application implements ApplicationInterface
{
    /** @var Authentication */
    private $authentication;

    private $board;

    public function __construct(Authentication $authentication, BoardInterface $board)
    {
        $this->board = $board;
        $this->authentication = $authentication;
    }

    public function run()
    {
        $this->authentication->login();
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader('../views'),
        ));
        echo $m->render('index', array('milestones' => $this->board->board()));
    }
}