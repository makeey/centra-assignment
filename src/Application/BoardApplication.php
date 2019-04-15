<?php

/**
 * This file part of `centra-assignment`.
 * Written by Anton Makeieiev <makeey97@gmail.com>
 */

declare(strict_types=1);

namespace KanbanBoard\Application;

use KanbanBoard\Infrastructure\Interfaces\Application;
use KanbanBoard\Infrastructure\Interfaces\Board as BoardInterface;
use Mustache_Engine;

class BoardApplication implements Application
{
    /** @var BoardInterface  */
    private $board;
    /** @var Mustache_Engine  */
    private $engine;

    public function __construct(BoardInterface $board, Mustache_Engine $engine)
    {
        $this->board = $board;
        $this->engine = $engine;
    }

    public function run(): void
    {
        echo $this->engine->render('index', ['milestones' => $this->board->board()]);
    }
}
