<?php

namespace Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * View
 */
class View {
	/**
	 * Render a view file
	 * @param string $view
	 * @param array $args
	 * @return void
	 */
	public static function render(string $view, array $args = []): void {
		extract($args, EXTR_SKIP);
		$file = "../App/Views/$view";    // relative to the Core directory
		if (is_readable($file))
			require $file;
		else
			echo "File not found";
	}

	/**
	 * Render a view template using Twig
	 *
	 * @param string $template The template file
	 * @param array $args Associative array of data to display in the view (optional)
	 * @return void
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
	public static function renderTemplate(string $template, array $args = []): void {
		static $twig = null;
		if ($twig === null) {
			$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
			$twig = new \Twig\Environment($loader);
		}
		echo $twig->render($template, $args);
	}
}