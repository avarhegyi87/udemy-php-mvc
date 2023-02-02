<?php

namespace App\Controllers;

use \Core\View;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Home controller
 */
class Home extends \Core\Controller {
	/**
	 * Before filter
	 * @return void
	 */
	protected function before(): void {
		echo "(before) ";
	}

	/**
	 * After filter
	 * @return void
	 */
	protected function after(): void {
		echo " (after)";
	}

	/**
	 * Show the index page
	 * @return void
	 */
	public function indexAction(): void {
		/*View::render('Home/index.php', [
			'name' => 'Adam',
			'colours' => ['red', 'green', 'blue']
		]);*/
		try {
			View::renderTemplate('Home/index.html', [
				'name' => 'Adam',
				'colours' => ['red', 'green', 'blue']
			]);
		} catch (LoaderError|RuntimeError|SyntaxError $e) {
		}
	}

}