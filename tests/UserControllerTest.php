<?php

use PHPUnit\Framework\TestCase;
use HRMS\Controllers\UserController;
use HRMS\Services\UserService;
use HRMS\Core\Session;
use HRMS\Models\UserModel;
use HRMS\Core\Controller;

class UserControllerTest extends TestCase
{
    private $userController;
    private $userServiceMock;
    private $repoMock;
    private $sessionMock;


    protected function setUp(): void
    {

        // Mocking Session Class
        $this->sessionMock = $this->createMock(Session::class);
        $this->userServiceMock = $this->createMock(UserService::class);
// Create a partial mock for UserController
        $this->userController = $this->getMockBuilder(UserController::class)
            ->onlyMethods(['render']) // Prevent `render()` execution
            ->setConstructorArgs([$this->userServiceMock]) // Pass constructor arguments
            ->getMock();
// Expect the render method to be called once but not executed
        $this->userController->expects($this->once())
            ->method('render')
            ->willReturn(true);
    }


    public function testLoginFailed()
    {

        $name = 'test';
        $password = 'password123';
        $this->userServiceMock->method('login')->willReturn(false);
// Call the login method (which calls render internally)
        $result =  $this->userController->loginUser($name, $password);
        $this->assertFalse($result);
    }


    public function testRegistration()
    {

        $name = 'test';
        $password = 'password123';
        $confirmPassword = 'password123';
        $email = 'test@test.com';
        $this->userServiceMock->method('register')->willReturn(true);
// Call the login method (which calls render internally)
        $result =  $this->userController->registerUser($name, $email, $password, $confirmPassword);
        $this->assertTrue($result);
    }

    public function testRegistrationError()
    {

        $name = 'test';
        $password = 'password123';
        $confirmPassword = 'password123';
        $email = 'test@test.com';
        $this->userServiceMock->method('register')->willReturn(false);
// Call the login method (which calls render internally)
        $result =  $this->userController->registerUser($name, $email, $password, $confirmPassword);
        $this->assertFalse($result);
    }
}
