<?php


namespace KanbanBoard;


use KanbanBoard\Infrastructure\ApplicationInterface;
use KanbanBoard\Infrastructure\Board as BoardInterface;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class BoardApplication implements ApplicationInterface
{
    private $board;
    private $engine;
    public function __construct(BoardInterface $board, Mustache_Engine $engine)
    {
        $this->board = $board;
        $this->engine = $engine;
    }

    public function run()
    {
        echo $this->engine->render('index', array('milestones' => $this->board->board()));
    }
}