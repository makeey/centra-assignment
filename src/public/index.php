<?php

use KanbanBoard\Infrastructure\ApplicationInterface;

$container = require_once '../../bootstrap.php';

$container->call(static function(ApplicationInterface $application){
   $application->run();
});