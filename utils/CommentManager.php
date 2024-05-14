<?php

namespace Utils;

use Interfaces\DatabaseInterface;
use App\Comment;

/** 
 * Class for comment manager
 * Findings:
 * Singleton Pattern and Code duplication - it can lead to tight coupling and make the code harder to test and maintain.
 * Remove the getInstance() duplication in multiple classes since the class can be instantiated via Dependency Injection
 * Change the constructor access modifier from private to public so that I can access the class outside this class.
 * Inject the DatabaseInterface in constructor to prevent duplication of instantiating the DB class for every method.
 ** This applies Dependency Inversion Principle making it easier to maintain and extend.
 * Direct SQL Queries - It can lead to SQL injection
 * Remove direct SQL queries and changed to parameterized queries to prevent SQL injections
 * Consistency - Usage of '' instead of "" for consistency
 * Lack of error handling - The code does not handle potential errors that may occur during operations.
 * 
 * @Author: Alvin Dela Cruz <delacruzalvinstaana@gmail.com>
 * @Date: 2024-05-14 
 */
class CommentManager
{

	/**
	 * @var DatabaseInterface
	 */
	private $db;

	/**
	 * Constructor class of comment manager
	 *
	 * @param DatabaseInterface $db
	 */
	public function __construct(DatabaseInterface $db)
	{
		$this->db = $db;
	}
	
	/**
	 * function to get all comments
	 *
	 * @return array
	 */
	public function listComments(): array
	{
		try {
			$rows = $this->db->select('SELECT * FROM `comment`');

			$comments = [];
			foreach($rows as $row) {
				$n = new Comment();
				$comments[] = $n->setId($row['id'])
				->setBody($row['body'])
				->setCreatedAt($row['created_at'])
				->setNewsId($row['news_id']);
			}

			return $comments;
		} catch (\PDOException $exception) {
			error_log('Error fetching comments: ' . $exception->getMessage());
            return [];
		}
	}

	/**
	 * function to add comments for news
	 *
	 * @param string $body
	 * @param int $newsId
	 * @return string|null
	 */
	public function addCommentForNews($body, $newsId): string|null
	{
		try {
			$sql = 'INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES(:body, :created_at, :news_id)';
			$params = [
				':body'       => $body, 
				':created_at' => date('Y-m-d'), 
				':news_id'    => $newsId
			];
			$this->db->exec($sql, $params);
			return $this->db->lastInsertId($sql);
		} catch (\PDOException $exception) {
			error_log('Error adding comments: ' . $exception->getMessage());
            return null;
		}
	}

	/**
	 * function to delete comment
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function deleteComment($id): bool
	{
		try {
			$sql = 'DELETE FROM `comment` WHERE `id` = :id';
			$params = [':id' => $id];
			return $this->db->exec($sql, $params);
		} catch (\PDOException $exception) {
			error_log('Error deleting comments: ' . $exception->getMessage());
            return false;
		}
	}
}