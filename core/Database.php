<?php

namespace core;

use \src\Config;

class Database
{
    private static $_pdo;
    public static function getInstance()
    {
        if (!isset(self::$_pdo)) {
            self::$_pdo = new \PDO(
                Config::DB_DRIVER . ":dbname=" .
                    Config::DB_DATABASE . ";host=" .
                    Config::DB_HOST,
                Config::DB_USER,
                Config::DB_PASS,
                array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
        }
        return self::$_pdo;
    }
}
