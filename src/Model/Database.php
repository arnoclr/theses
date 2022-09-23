<?php

namespace App\Model;

use PDO;

class Database
{
    public static function getPDO(): PDO
    {
        $pdo = new \PDO('mysql:host=localhost;dbname=theses;charset=utf8', 'root', '');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        return $pdo;
    }
}
