<?php

namespace App;

/**
 * Class for news getter and setter
 * @Author: Alvin Dela Cruz <delacruzalvinstaana@gmail.com>
 * @Date: 2024-05-14 
 */
class News
{
	protected $id, $title, $body, $createdAt;

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setBody($body)
	{
		$this->body = $body;

		return $this;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}
}