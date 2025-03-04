<?php

use HRMS\Models\UserModel;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private $userModel;


    protected function setUp(): void {
        // Mock the PDO and PDOStatement
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);

        // Configure PDO mock to return a statement mock on prepare
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);

        // Initialize the User model with the mocked PDO
        $this->userModel = new UserModel($this->pdoMock);
    }

    public function testRegisterSuccess() {
        // Simulate isEmailTaken returning false (email is not taken)
        $this->stmtMock->method('fetch')->willReturn(false);
        
        // Expect execute() to return true (successful registration)
        $this->stmtMock->method('execute')->willReturn(true);

        $result = $this->userModel->register("test", "password123", "test@example.com");
        $this->assertTrue($result);
    }

    public function testRegisterFailsWhenUsernameExists() {
        // Simulate isEmailTaken returning true (email is already taken)
        $this->stmtMock->method('fetch')->willReturn(["id" => 1]);

        $result = $this->userModel->register("test", "test@example.com", "password123");
        $this->assertFalse($result);
    }

    public function testLoginSuccess() {
        // Simulate fetching a hashed password
        $hashedPassword = password_hash("password123", PASSWORD_DEFAULT);
        $this->stmtMock->method('fetch')->willReturn(["password" => $hashedPassword]);

        $result = $this->userModel->login("test", "password123");
        $this->assertTrue($result);
    }

    public function testLoginFailsWithWrongPassword() {
        // Simulate fetching a hashed password
        $hashedPassword = password_hash("password123", PASSWORD_DEFAULT);
        $this->stmtMock->method('fetch')->willReturn(["password" => $hashedPassword]);

        $result = $this->userModel->login("test", "wrongpassword");
        $this->assertFalse($result);
    }

    public function testLoginFailsWhenUserNotFound() {
        // Simulate no user found
        $this->stmtMock->method('fetch')->willReturn(false);

        $result = $this->userModel->login("invalidUser", "password123");
        $this->assertFalse($result);
    }
}


    
