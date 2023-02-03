<?php

namespace Core;

use App\Config;
use PDO;

/**
 * Base model
 */
abstract class Model {
	/**
	 * Get the PDO database connection
	 * @return PDO
	 */
	protected static function getDB(): PDO {
		static $db = null;
		if ($db === null) {
			$dsn = "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=utf8";
			$db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

			// Throw an exception when an error occurs
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return $db;
	}

}