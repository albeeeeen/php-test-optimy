<?php

namespace Interfaces;

/**
 * Interface for database
 * @Author: Alvin Dela Cruz <delacruzalvinstaana@gmail.com>
 * @Date: 2024-05-14 
 */
interface DatabaseInterface {
    public function select($sql, $params = []);
    public function exec($sql, $params = []);
    public function lastInsertId();
}