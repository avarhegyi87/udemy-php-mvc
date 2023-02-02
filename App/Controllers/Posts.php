<?php

namespace App\Controllers;

use Core\View;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Posts controller
 */
class Posts extends \Core\Controller {
	/**
	 * Show the index page
	 * @return void
	 */
	public function indexAction(): void {
		// echo 'Hello from the index action in the Posts controller.';
		// echo '<p>Query string parameters: <pre>' . htmlspecialchars(print_r($_GET, true)) . '</pre></p>';
		try {
			View::renderTemplate('Posts/index.html');
		} catch (LoaderError|RuntimeError|SyntaxError $e) {
		}
	}

	/**
	 * Show the add new page
	 * @return void
	 */
	public function addNewAction() {
		echo "Hello from the addNew action in the Posts controller";
	}

	public function editAction() {
		echo 'Hello from the edit action in the Posts controller!';
		echo '<p>Route parameters: <pre>' . htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
	}
}