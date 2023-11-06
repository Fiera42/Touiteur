<?php

namespace touiteur\src\Auth;

class ConnexionFactory
{
    private static $tconfig;
    private static $pdo;

    static function  setConfig($file)
    {
        self::$tconfig = parse_ini_file($file);
    }
    public static function makeconexion()
    {
        if (!isset(self::$pdo)) {


            $driver = self::$tconfig['driver'];
            $host = self::$tconfig['host'];
            $database = self::$tconfig['database'];
            $dsn = "$driver:$host;dbname=$database; charset=utf8";
            $username = self::$tconfig['username'];
            var_dump(self::$tconfig);
            self::$pdo = new PDO($dsn, $username, "");
        }
        return static:: $pdo;
    }
}