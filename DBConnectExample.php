<?php

/**
 * Manage the connection between the application and the database using PDO.
 */
class DbConnect
{
    /**
     * Connection static function.
     * @return PDO
     */
	public static function connection()
	{
		$connection = new PDO(
			'mysql:host=localhost;dbname=', '', ''
		);

		$connection->exec('SET NAMES utf8');

		return $connection;
	}
}