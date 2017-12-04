<?php
/**
 * Created by PhpStorm.
 * User: CW
 * Date: 12/4/2017
 * Time: 9:10 AM
 */

class DatabaseConnection
{
    private static $instance = null;
    private static $host = "localhost";
    private static $dbname = "W00211649";
    private static $user = "W00211649";
    private static $pass = "Christophercs!";

    private function __construct()
    {

    }

    public static function getInstance(): \PDO
    {
        if (!static::$instance === null) {
            return static::$instance;
        } else {
            try {
                $connectionString = "mysql:host=".static::$host.";dbname=".static::$dbname;
                static::$instance = new \PDO($connectionString, static::$user, static::$pass);
                static::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return static::$instance;
            } catch (PDOException $e) {
                echo "Unable to connect to the database: " . $e->getMessage();
                die();
            }
        }
    }
}