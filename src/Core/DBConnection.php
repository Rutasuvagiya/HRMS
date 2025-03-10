<?php

namespace HRMS\Core;

use PDO;
use PDOException;

/**
 * Class DBConnection
 *
 * This class provides database connection object
 * Singleton pattern is applied to this class to restrice multiple instance
 * DB connection is established once and reused across the application.
 *
 */
class DBConnection
{
    private static $instance = null;
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $connection;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {

        $this->host = 'localhost';
        $this->dbname = 'HRMS';
        $this->username = 'root';
        $this->password = '';
    }

    // Private clone method to prevent cloning
    private function __clone()
    {
    }

    // Private wakeup method to prevent unserializing
    private function __wakeup()
    {
    }

    // Public method to get the single instance of the class
    public static function getInstance(): PDO
    {
        //Check if instance is not created then create new DB connection
        if (self::$instance === null) {
            try {
                //call constructor method to get properties of DB connection
                $dbObj = new static();

                //Set DB connection instance in static variable $instance
                self::$instance = new PDO(
                    "mysql:host=" . $dbObj->host . ";dbname=" . $dbObj->dbname,
                    $dbObj->username,
                    $dbObj->password
                );
                // set the PDO error mode to exception
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Handle connection error
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return self::$instance;
    }
}
