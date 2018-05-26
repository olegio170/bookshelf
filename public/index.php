<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/setup.php';

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


$router = new Core\Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);

$router->add('{controller}/{action}');

$router->add('book/view/{id:\d+}', ['controller' => 'Book', 'action' => 'view']);
$router->add('book/edit/{id:\d+}', ['controller' => 'Book', 'action' => 'edit']);
$router->add('book/delete/{id:\d+}', ['controller' => 'Book', 'action' => 'delete']);
    
$router->dispatch($_SERVER['QUERY_STRING']);