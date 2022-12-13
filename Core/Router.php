<?php

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
	 * @param string $route		The route URL
	 * @param array $params		Parameters (controller, action, etc.)
	 * @return void
	 */
	public function add(string $route, array $params = []) {
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
	 * @param string $url	The route URL
	 * @return bool			true if a match is found, false otherwise
	 */
	public function match(string $url): bool {

		foreach ($this->routes as $route => $params){
			if (preg_match($route, $url, $matches)) {
				foreach ($matches as $key => $match) {
					if (is_string($key)) {
						$params[$key] = $match;
					}
				}

				$this->params = $params;
				return true;
			}
		}

		return false;
	}

	/**
	 * Dispatch the route, creating the controller object and running the action method
	 *
	 * @param string $url	The route URL
	 * @return void
	 */
	public function dispatch(string $url) {
		if ($this->match($url)) {
			$controller = $this->convertToStudlyCaps($this->params['controller']);

			if (class_exists($controller)) {
				$controller_object = new $controller();

				$action = $this->convertToCamelCase($this->params['action']);

				if (is_callable([$controller_object, $action])) {
					$controller_object->$action();
				} else {
					echo "Method $action (in controller $controller) not found.";
				}
			} else {
				echo "Controller class $controller not found.";
			}
		} else {
			echo "No route matched.";
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



}