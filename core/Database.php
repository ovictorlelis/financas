<?php

namespace core;

class Database
{
    private static $_pdo;
    public static function getInstance()
    {
        if (!isset(self::$_pdo)) {
            self::$_pdo = new \PDO(
                getenv('DB_DRIVE')
                    . ":dbname=" . getenv('DB_BASE')
                    . ";host=" . getenv('DB_HOST'),
                getenv('DB_USER'),
                getenv('DB_PASS')
            );
        }
        return self::$_pdo;
    }
}
