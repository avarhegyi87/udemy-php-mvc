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
			try {
				$db = new PDO("mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=utf8",
							  Config::DB_USER, Config::DB_PASSWORD);
			} catch (\PDOException $e) {
				echo $e->getMessage();
			}
		}
		return $db;
	}

}