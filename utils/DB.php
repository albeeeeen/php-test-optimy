<?php

namespace Utils;

use Interfaces\DatabaseInterface;

/** 
 * Class for database
 * Findings:
 * Singleton Pattern and Code duplication - it can lead to tight coupling and make the code harder to test and maintain.
 * Remove the getInstance() duplication in multiple classes since the class can be instantiated via Dependency Injection
 * Hardcoded database credentials - Storing database credentials directly in the code is a security risk.
 * Separating the database connection for maintainability and to easily mock and test this class.
 * Inject the PDO instance into the class constructor to make the class more flexible and reusable, when you want to use different database.
 * Create interface for database. This will allow you to change the underlying database system or connection method
 ** without affecting the code that depends on it, making it flexible whenever you want to switch from other database.
 * Change the constructor access modifier from private to public so that I can access the class outside this class.
 * Update exec() and select() method and add $params parameter for parameterized queries to prevent SQL injections
 * Lack of error handling - The code does not handle potential errors that may occur during operations.
 * 
 * @Author: Alvin Dela Cruz <delacruzalvinstaana@gmail.com>
 * @Date: 2024-05-14 
 */
class DB implements DatabaseInterface
{
	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * Constructor class for DB
	 * 
	 * @param \PDO $pdo
	 */
	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	/**
	 * database function for select
	 *
	 * @param string $sql
	 * @param array $params
	 * @return array
	 */
	public function select($sql, $params = []): array
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll();
	}

	/**
	 * database function for exec
	 * 
	 * @param string $sql
	 * @param array $params
	 * @return boolean
	 */
	public function exec($sql, $params = []): bool
	{
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute($params);
	}

	/**
	 * function for getting the last inserted id
	 * 
	 * @return string
	 */
	public function lastInsertId(): string
	{
		return $this->pdo->lastInsertId();
	}
}