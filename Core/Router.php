<?php

namespace Core;

use Exception;

/**
 *
 */
class Router {
	/**
	 * Associative array of routes (the routing table)
	 * @var array
	 */
	protected array $routes = [];

	/**
	 * Parameters from the matched route
	 * @var array
	 */
	protected array $params = [];

	/**
	 * Add a route to the routing table
	 *
	 * @param string $route The route URL
	 * @param array $params Parameters (controller, action, etc.)
	 * @return void
	 */
	public function add(string $route, array $params = []): void {
		// Convert the line into a regular expression: escape forward slashes
		$route = preg_replace('/\//', '\\/', $route);

		// Convert variables, e.g. {controller}
		$route = preg_replace('/{([a-z-]+)}/', '(?P<\1>[a-z-]+)', $route);

		// Convert variables with custom regular expressions e.g. {id:\d+}
		$route = preg_replace('/{([a-z-]+):([^}]+)}/', '(?P<\1>\2)', $route);

		// Add start and end delimiters, and case-insensitive flag (i)
		$route = '/^' . $route . '$/i';

		$this->routes[$route] = $params;
	}

	/**
	 * Get all the routes from the routing table
	 *
	 * @return array
	 */
	public function getRoutes(): array {
		return $this->routes;
	}

	/**
	 * Match the route to the routes in the routing table, setting the $params property if a route is found
	 *
	 * @param string $url The route URL
	 * @return bool            true if a match is found, false otherwise
	 */
	public function match(string $url): bool {

		foreach ($this->routes as $route => $params)
			if (preg_match($route, $url, $matches)) {
				foreach ($matches as $key => $match)
					if (is_string($key))
						$params[$key] = $match;

				$this->params = $params;
				return true;
			}

		return false;
	}

	/**
	 * Dispatch the route, creating the controller object and running the action method
	 *
	 * @param string $url The route URL
	 * @return void
	 * @throws Exception
	 */
	public function dispatch(string $url): void {
		$url = $this->removeQueryStringVariables($url);

		if ($this->match($url)) {
			$controller = $this->getNamespace() . $this->convertToStudlyCaps($this->params['controller']);

			if (class_exists($controller)) {
				$controller_object = new $controller($this->params);

				$action = $this->convertToCamelCase($this->params['action']);

				if (is_callable([$controller_object, $action]))
					$controller_object->$action();
				else
					throw new Exception("Method $action in (in controller $controller) not found");
			} else {
				throw new Exception("Controller class $controller not found");
			}
		} else {
			throw new Exception("No route matched", 404);
		}
	}

	/**
	 * Convert the string with hyphens to StudlyCaps, e.g. post-authors => PostAuthors
	 *
	 * @param string $string The string to convert
	 * @return string
	 */
	protected function convertToStudlyCaps(string $string): string {
		return str_replace(' ', '', ucwords(str_replace('-', '', $string)));
	}

	/**
	 * Convert the string with hyphens to camelCase, e.g. add-new => addNew
	 *
	 * @param $string
	 * @return string
	 */
	protected function convertToCamelCase($string): string {
		return lcfirst($this->convertToStudlyCaps($string));
	}

	/**
	 * Get the currently matched parameters
	 *
	 * @return array
	 */
	public function getParams(): array {
		return $this->params;
	}

	/**
	 * Remove the query string variables from the URL (if any).
	 * As the full query string is used for the route, any variables at the end will need to be removed
	 * before the route is matched to the routing table. For example:
	 *
	 * 	URL									$_SERVER['QUERY STRING']	Route
	 * 	-------------------------------------------------------------------------------------
	 * 	localhost							''							''
	 * 	localhost/?							''							''
	 * 	localhost/?page=1					page=1						''
	 * 	localhost/posts?page=1				posts&page=1				posts
	 * 	localhost/posts/index				posts/index					posts/index
	 * 	localhost/posts/index?page=1		posts/index&page=1			posts/index
	 *
	 * A URL of the format localhost/?page (one variable name, no value) won't work however.
	 * (NB. The .htaccess file converts the first ? to & when it's passed through to the $_SERVER variable).
	 *
	 * @param string $url 	The full URL
	 * @return string       The URL with the query string variables removed
	 */
	protected function removeQueryStringVariables(string $url): string {
		if ($url != '') {
			$parts = explode('&', $url, 2);

			if (!str_contains($parts[0], '='))
				$url = $parts[0];
			else
				$url = '';
		}
		return $url;
	}

	/**
	 * Get the namespace for the controller class.
	 * The namespace defined in the route parameters is added if present.
	 * @return string	The request URL
	 */
	protected function getNamespace(): string {
		$namespace = 'App\Controllers\\';
		if (array_key_exists('namespace', $this->params))
			$namespace .= $this->params['namespace'] . '\\';
		return $namespace;
	}
}