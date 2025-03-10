<?php

use PHPUnit\Framework\TestCase;
use HRMS\Services\UserService;
use HRMS\Models\UserModel;
use HRMS\Models\PackageModel;
use HRMS\Core\Validator;
use HRMS\Core\Session;
use HRMS\Core\Notifier;

class UserServiceTest extends TestCase
{
    private $repoMock;
    private $packageMock;
    private $validator;
    private $userService;
    private $session;
    private $notifier;

    protected function setUp(): void
    {

        $this->repoMock = $this->createMock(UserModel::class);
        $this->packageMock = $this->createMock(PackageModel::class);
        $this->validator = new Validator();
        $this->userService = new UserService($this->repoMock);
        Session::enableTestMode();
        $this->session = Session::getInstance();
        $this->notifier = Notifier::getInstance();
    }

    public function testEmptyName()
    {

        $this->userService->register("", "test@test.com", "Password123", "Password123");
        $this->assertArrayHasKey("username", $this->getErrors());
    }

    /**
     * @dataProvider NameValidationDataProvider
     */
    public function testInValidUsername($username)
    {
        $result = $this->userService->register($username, "test@test.com", "Password123", "Password123");
        $this->assertArrayHasKey("username", $this->getErrors(), $username . ' is valid');
    }
    public function NameValidationDataProvider()
    {
        return [
            // Invalid input

            ["!name"],
            ["hello!"],
            ["world.wide"],
            ["a b c"]
        ];
    }

    public function testEmptyEmail()
    {
        $this->userService->register("test", "", "Password123", "Password123");
        $this->assertArrayHasKey("email", $this->getErrors());
    }

     /**
     * @dataProvider EmailValidationDataProvider
     */
    public function testInvalidEmail($email)
    {
        $this->userService->register("TestUser", $email, "Password123", "Password123");
        $this->assertArrayHasKey('email', $this->getErrors(), $email . " is a valid email");
    }
    public function EmailValidationDataProvider()
    {
        return [
            // Invalid input
            ["invalid-mail"],
            ["test"],
            ["mail.com"],
            ["test@test"],
            [".email@example.com"],
            ["email.@example.com"],
            ["email..email@example.com"],
            ["email@111.222.333.44444"],
            ["email@example..com"]
        ];
    }


    public function testEmptyPassword()
    {
        $this->userService->register("test", "test@test.com", "", "Password123");
        $this->assertArrayHasKey("password", $this->getErrors());
    }

    public function testShortPassword()
    {
        $this->userService->register("TestUser", "test@test.com", "123", "123");
        $this->assertArrayHasKey("password", $this->getErrors());
    }

    public function testEmptyConfirmPassword()
    {
        $this->userService->register("test", "test@test.com", "Password123", "");
        $this->assertArrayHasKey("confirmPassword", $this->getErrors());
    }

    public function testPasswordMismatch()
    {
        $this->userService->register("TestUser", "test@test.com", "Password123", "WrongPassword");
        $this->assertArrayHasKey("confirmPassword", $this->getErrors());
    }

    /**
     * @dataProvider registrationDataProvider
     */
    public function testValidRegistration($name, $email, $password, $confirmPassword, $expected)
    {
        // Simulate that email is NOT taken
        $this->repoMock->method('isUsernameTaken')->willReturn(false);
// Simulate successful user saving
        $this->repoMock->method('register')->willReturn(true);
        $this->assertEquals($expected, $this->userService->register($name, $email, $password, $confirmPassword));
    }

    public function registrationDataProvider()
    {
        return [
            ["Test User", "test@test.com", "password123", "password123", false], //invalid username
            ["testuser", "invalid-email", "password123", "password123", false], //invalid email
            ["TestUser", "test@test.com", "password123", "password456", false], //password mismatch
            ["TestUser", "test@test.com", "1234", "1234", false],               //password invalid length
            ["TestUser", "test@test.com", "password123", "password123", true],  //valid inputs
        ];
    }

    /**
     * @dataProvider registrationDataProvider1
     */
    public function testInvalidRegistration($name, $email, $password, $confirmPassword, $expected)
    {
        // Simulate that email is NOT taken
        $this->repoMock->method('isUsernameTaken')->willReturn(false);
// Simulate successful user saving
        $this->repoMock->method('register')->willReturn(false);
        $this->assertEquals($expected, $this->userService->register($name, $email, $password, $confirmPassword));
    }

    public function registrationDataProvider1()
    {
        return [
            ["TestUser", "test@test.com", "password123", "password123", false],  //valid inputs
        ];
    }
    /**
     * @dataProvider registrationDataProvider1
     */
    public function testRegistrationDBError($name, $email, $password, $confirmPassword, $expected)
    {

         // Simulate that email is NOT taken
         $this->repoMock->method('isUsernameTaken')->willReturn(false);
        $this->repoMock->method('register')->willThrowException(new PDOException("Connection failed"));
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Connection failed");
        $result = $this->userService->register($name, $email, $password, $confirmPassword);
        //$this->assertStringContainsString("Connection failed", $result);
    }

    /**
     * @dataProvider loginDataProvider
     */
    public function testValidLogin($name, $password, $expected)
    {

        // Simulate successful user saving
        $this->repoMock->method('login')->willReturn(true);
        $this->session->set('role', 'patient');
        $this->packageMock->method('getExpiringPackages')->willReturn(0);
        $this->assertEquals($expected, $this->userService->login($name, $password));
    }

    //Invalid credentials verification
    public function testInvalidLogin()
    {

        $name = 'notvalid';
        $password = '123456';
// Simulate unsuccessful user login
        $this->repoMock->method('login')->willReturn(false);
        $this->assertEquals(false, $this->userService->login($name, $password));
    }

    public function loginDataProvider()
    {
        return [
            ["",  "password123", false], //invalid username
            ["testuser", "", false], //invalid email
            ["", "", false], //invalid email
            ["TestUser",  "password123", true]  //valid inputs
        ];
    }

    public function testAdminDashboard()
    {

        $this->repoMock->method('getDashboard')->willReturn('adminDashboard');
        $result = $this->userService->getDashboard();
        $this->assertEquals('adminDashboard', $result);
    }

    public function getErrors()
    {
        $errors = $this->userService->getErrors();
        return  $errors['error'];
    }
/*
    public function tearDown()
    {
        $this->repoMock = null;
        $this->packageMock = null;
    }
  */
}
