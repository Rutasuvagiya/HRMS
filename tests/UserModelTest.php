<?php

use PHPUnit\Framework\TestCase;
use HRMS\Models\UserModel;

class UserModelTest extends TestCase
{
    private $mockPDO;
    private $mockStatement;
    private $userModel;
    private $userModelMock;

    protected function setUp(): void
    {
        /// Create PDO and Statement Mocks
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);

        // Mock the prepare method to return the statement mock
        $this->mockPDO->method('prepare')->willReturn($this->mockStatement);

        // Instantiate UserModel with the mock PDO
        $this->userModel = new UserModel($this->mockPDO);
    }

    //Test registration successful
    public function testRegisterUser()
    {
        $this->mockStatement->method('execute')->willReturn(true);
        $result = $this->userModel->register("test", "password123", "test@example.com");

        // Ensure that registration is successful (true)
        $this->assertTrue($result);
    }

    //Test cache part of registration
    public function testRegistrationFailed()
    {
        // Simulate PDO throwing a PDOException when prepare() is called
        $this->mockPDO->method('prepare')->willThrowException(new PDOException("Error: Connection failed"));

        // Expect the Exception to be thrown
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Error: Connection failed");


        $result = $this->userModel->register("test", "password123", "test@example.com");
    }

    //Test patient login
    public function testPatientLogin()
    {

        $name = "test";
        $password = "password123";
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Mock fetch result (fake user data)
        $this->mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'name' => 'Test',
            'role' => 'patient',
            'password' => $hashedPassword
        ]);

        $this->mockStatement->method('execute')->willReturn(true);

        // Test valid login
        $result = $this->userModel->login($name, $password);
        $this->userModel->getDashboard();
        $this->assertTrue($result);
    }

    //Test admin login
    public function testAdminLogin()
    {

        $name = "test";
        $password = "password123";
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Mock fetch result (fake user data)
        $this->mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'name' => 'Test',
            'role' => 'admin',
            'password' => $hashedPassword
        ]);

        $this->mockStatement->method('execute')->willReturn(true);

        // Test valid login
        $result = $this->userModel->login($name, $password);
        $this->userModel->getDashboard();
        $this->assertTrue($result);
    }

    //Test invalid creds
    public function testFailedLogin()
    {

        $name = "test";
        $password = "password123";

        // Mock fetch result (fake user data)
        $this->mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'name' => 'Test',
            'role' => 'patient',
            'password' => 'hased'
        ]);

        $this->mockStatement->method('execute')->willReturn(true);

        // Test valid login
        $result = $this->userModel->login($name, $password);

        $this->assertFalse($result);
    }

    //Test cache section of login
    public function testLoginException()
    {

        // Simulate PDO throwing a PDOException when prepare() is called
        $this->mockPDO->method('prepare')->willThrowException(new PDOException("Error: Connection failed"));

        // Expect the Exception to be thrown
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Error: Connection failed");
         $this->userModel->login("test", "password123");
    }

    //Test username is already exists true scenario
    public function testUsernameExists()
    {
        $name = "test";

        // Simulate a user record found
        $this->mockStatement->method('rowCount')->willReturn(1);

        $this->mockStatement->method('execute')->willReturn(true);

        // Test if email exists
        $result = $this->userModel->isUsernameTaken($name);

        $this->assertTrue($result);
    }

    //Test valid name of isUsernameTaken
    public function testUsernameDoesNotExist()
    {
        $name = "testInvalid";

        // Simulate no user found
        $this->mockStatement->method('rowCount')->willReturn(0);

        $this->mockStatement->method('execute')->willReturn(true);

        // Test if email does not exist
        $result = $this->userModel->isUsernameTaken($name);

        $this->assertFalse($result);
    }

    //Test cache part of isUsernameTaken
    public function testUsernameDatabaseException()
    {
        $name = "testExeption";

        // Simulate a database error
        $this->mockStatement->method('execute')->willThrowException(new \PDOException("Database error"));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Database error");

        $this->userModel->isUsernameTaken($name);
    }
}
