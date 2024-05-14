<?php

namespace Utils;

use Utils\CommentManager;
use App\News;
use Interfaces\DatabaseInterface;

/** 
 * Class for news manager
 * Findings:
 * Singleton Pattern and Code duplication - it can lead to tight coupling and make the code harder to test and maintain.
 * Remove the getInstance() duplication in multiple classes since the class can be instantiated via Dependency Injection
 * Change the constructor access modifier from private to public so that I can access the class outside this class.
 * Inject the DatabaseInterface in constructor to prevent instantiating the DB class for every method.
 ** This applies Dependency Inversion Principle making it easier to maintain and extend.
 * Inject the CommentManager class in constructor to prevent instantiating the class for every method
 * Direct SQL Queries - It can lead to SQL injection
 * Remove direct SQL queries and changed to parameterized queries to prevent SQL injections
 * Consistency - Usage of '' instead of "" for consistency
 * Lack of error handling - The code does not handle potential errors that may occur during operations.
 * 
 * @Author: Alvin Dela Cruz <delacruzalvinstaana@gmail.com>
 * @Date: 2024-05-14 
 */
class NewsManager
{

	/**
	 * @var DatabaseInterface
	 */
	private $db;

	/**
	 * @var CommentManager
	 */
	private $commentManager;

	/**
	 * Constructor class for news manager
	 *
	 * @param DatabaseInterface $db
	 * @param CommentManager $commentManager
	 */
	public function __construct(DatabaseInterface $db, CommentManager $commentManager)
	{
		$this->db = $db;
		$this->commentManager = $commentManager;
	}

	/**
	 * list all news
	 *
	 * @return array
	 */
	public function listNews(): array
	{
		try {
			$rows = $this->db->select('SELECT * FROM `news`');

			$news = [];
			foreach($rows as $row) {
				$n = new News();
				$news[] = $n->setId($row['id'])
				->setTitle($row['title'])
				->setBody($row['body'])
				->setCreatedAt($row['created_at']);
			}

			return $news;
		} catch (\PDOException $exception) {
			error_log('Error fetching news: ' . $exception->getMessage());
            return [];
		}
	}

	/**
	 * add a record in news table
	 *
	 * @param string $title
	 * @param string $body
	 * @return string|null
	 */
	public function addNews($title, $body): string|null
	{
		try {
			$sql = 'INSERT INTO `news` (`title`, `body`, `created_at`) VALUES(:title, :body, :created_at)';
			$params = [
				':title' 	  => $title,
				':body' 	  => $body,
				':created_at' => date('Y-m-d')
			];
			$this->db->exec($sql, $params);
			return $this->db->lastInsertId($sql);
		} catch (\PDOException $exception) {
			error_log('Error adding news: ' . $exception->getMessage());
            return null;
		}
	}

	/**
	 * deletes a news
	 *
	 * @param int $id
	 * @return mixed
	 */
	public function deleteNews($id): mixed
	{
		try {
			$this->deleteLinkedComments($id);
			$sql = 'DELETE FROM `news` WHERE `id` = :id';
			$params = [':id' => $id];
			return $this->db->exec($sql, $params);
		} catch (\PDOException $exception) {
			error_log('Error deleting news: ' . $exception->getMessage());
            return false;
		}
	}

	/**
	 * delete linked comments
	 * Finding:
	 * Multiple responsibility - separate this function so that the delete news has single responsibility
	 * @param int $id
	 * @return void
	 */
	private function deleteLinkedComments($id): void
	{
		try {
			$comments = $this->commentManager->listComments();

			/**
			 * delete the comment using the id from $comment->getId() instead of storing it to another array
			 * to reduce the iterations
			 */		
			foreach ($comments as $comment) {
				if ($comment->getNewsId() == $id) {
					$this->commentManager->deleteComment($comment->getId());
				}
			}
		} catch (\PDOException $exception) {
			error_log('Error deleting linked comments: ' . $exception->getMessage());
		}
	}
}