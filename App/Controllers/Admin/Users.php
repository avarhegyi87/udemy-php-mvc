<?php

namespace App\Controllers\Admin;

/**
 * User admin controller
 */
class Users extends \Core\Controller {
	/**
	 * Before filter
	 * @return bool
	 */
	protected function before(): bool {
		return false;
	}

	/**
	 * Show the index page
	 * @return void
	 */
	public function indexAction() {
		echo 'User admin index';
	}
}