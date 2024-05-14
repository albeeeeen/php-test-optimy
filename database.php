<?php

/**
 * Separate the database connection code and put it here to separate it from database logic.
 ** It also makes it easier to replace the database connection.
 * Use config file for database configuration so you just need to update the config file whenever there are changes in the database configuration
 */

use Utils\DB;

$config = include 'config.php';

$pdo = new PDO('mysql:dbname=' . $config['database']['name'] . ';host=' . $config['database']['host'], $config['database']['user'], $config['database']['password']);
$db = new DB($pdo);