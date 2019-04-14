<?php


namespace KanbanBoard;


use KanbanBoard\Infrastructure\ApplicationInterface;
use KanbanBoard\Infrastructure\Board as BoardInterface;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class BoardApplication implements ApplicationInterface
{
    private $board;

    public function __construct(BoardInterface $board)
    {
        $this->board = $board;
    }

    public function run()
    {
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader('../views'),
        ));
        echo $m->render('index', array('milestones' => $this->board->board()));
    }
}