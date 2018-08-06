<?php namespace Mini;

class SuperPDO
{
    private static  $pdo;

    static public function connect($config) {
        // co a une class privé
        static::$pdo = new \PDO($config['dsn'],$config['user'],$config['password']);


    }
}