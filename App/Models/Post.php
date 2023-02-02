<?php

namespace App\Models;

use PDO;

/**
 * Post model
 */
class Post extends \Core\Model {
	/**
	 * Get all the posts in an associative array
	 * @return array|null
	 */
	public static function getAll(): ?array {
		try {
			$db = static::getDB();
			$stmt = $db->query('SELECT id, title, content FROM posts ORDER BY created_at');
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (\PDOException $e) {
			echo $e->getMessage();
			return null;
		}
	}
}