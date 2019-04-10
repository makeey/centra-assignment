<?php

require_once '../../bootstrap.php';
$repositories = explode('|', getenv('GH_REPOSITORIES'));
$authentication = new \KanbanBoard\Authentication();
$token = $authentication->login();
$github = new \KanbanBoard\Github($token, getenv('GH_ACCOUNT'));
$board = new \KanbanBoard\Application($github, $repositories, array('waiting-for-feedback'));
$data = $board->board();
$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader('../views'),
));
echo $m->render('index', array('milestones' => $data));
