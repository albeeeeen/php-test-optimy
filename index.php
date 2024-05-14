<?php

/**
 * Findings:
 * Remove the usage of require_once in including PHP file in another PHP file
 ** Applied require_once only if necessary.
 * Usage of composer autoloading to autoload classes for dependency management and to organize classes.
 ** It simplifies class loading and reduces the amount of boilerplate code required.
 */

require_once 'vendor/autoload.php';
require_once 'database.php';

use Utils\NewsManager;
use Utils\CommentManager;

$commentManager = new CommentManager($db);
$newsManager = new NewsManager($db, $commentManager);

foreach ($newsManager->listNews() as $news) {
	echo("############ NEWS " . $news->getTitle() . " ############\n");
	echo($news->getBody() . "\n");

	//add spacing for readability specially for conditions
	foreach ($commentManager->listComments() as $comment) {
		if ($comment->getNewsId() == $news->getId()) {
			echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
		}
	}
}

$c = $commentManager->listComments();