<?php

/* Front controller */

// Composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Routing
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{action}/{controller}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

try {
	$router->dispatch($_SERVER['QUERY_STRING']);
} catch (Exception $e) {
}
