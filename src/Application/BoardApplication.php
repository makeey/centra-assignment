<?php


namespace KanbanBoard\Application;


use KanbanBoard\Infrastructure\Interfaces\Application;
use KanbanBoard\Infrastructure\Interfaces\Board as BoardInterface;
use Mustache_Engine;

class BoardApplication implements Application
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