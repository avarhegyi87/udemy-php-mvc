<?php

/* Front controller */

// Require the controller class
require '../App/Controllers/Posts.php';

// Routing
require "../Core/Router.php";

$router = new Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{action}/{controller}');

/*// Display the routing table
echo '<pre>';
echo htmlspecialchars(print_r($router->getRoutes(), true));
echo '</pre>';

// Match the requested route
$url = $_SERVER['QUERY_STRING'];


if ($router->match($url)) {
	echo '<pre>';
	var_dump($router->getParams());
	echo '</pre>';
} else {
	echo "No route found for URL '$url'";
}*/

$router->dispatch($_SERVER['QUERY_STRING']);
