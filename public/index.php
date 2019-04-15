<?php

use KanbanBoard\Infrastructure\Interfaces\Application;

$container = require_once '../bootstrap.php';

$container->call(static function(Application $application){
   $application->run();
});