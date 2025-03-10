<?php

use HRMS\Core\DBConnection;
use PHPUnit\Framework\TestCase;

class DBConnectionTest extends TestCase
{
    public function testGetDBObject()
    {
        $db = DBConnection::getInstance();

        $this->assertNotNull($db, 'Database connection is null');
        $this->assertInstanceOf(PDO::class, $db, 'Database connection is not an instance of PDO');
    }

    public function testSingletonInstance()
    {
        $db1 = DBConnection::getInstance();
        $db2 = DBConnection::getInstance();

        $this->assertSame($db1, $db2, "Both instances should be the same (Singleton).");
    }
}
