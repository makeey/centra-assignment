<?php

use KanbanBoard\Authentication;
use KanbanBoard\Board;
use KanbanBoard\Github;
use KanbanBoard\Infrastructure\SessionTokenProvider;

require_once '../../bootstrap.php';
$repositories = explode('|', getenv('GH_REPOSITORIES'));
$authentication = new Authentication(getenv('GH_CLIENT_ID'), getenv('GH_CLIENT_SECRET'));
$token = $authentication->login();
$tokenProvider = new SessionTokenProvider();
$github = new Github($tokenProvider, getenv('GH_ACCOUNT'));
$board = new Board($github, $repositories, array('waiting-for-feedback'));
$data = $board->board();
$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../views'),
));
echo $m->render('index', array('milestones' => $data));
