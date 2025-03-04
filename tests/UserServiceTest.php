<?php
use PHPUnit\Framework\TestCase;
use HRMS\Services\UserService;
use HRMS\Models\UserModel;
use HRMS\Validator;

class UserServiceTest extends TestCase {

    private $repoMock;
    private $validator;
    private $userService;

    protected function setUp(): void {
        $this->repoMock = $this->createMock(UserModel::class);
        $this->validator = new Validator();
        $this->userService = new UserService($this->repoMock, $this->validator);
    }

    public function testEmptyName() {
        $this->userService->register("", "test@test.com", "Password123", "Password123");
        $this->assertArrayHasKey("username", $this->validator->getErrors());
    }

    /**
     * @dataProvider NameValidationDataProvider
     */
    public function testInValidUsername($username)
    {
        $result = $this->userService->register($username, "test@test.com", "Password123", "Password123");
        $this->assertArrayHasKey("username", $this->validator->getErrors(), $username .' is valid');

    }
    public function NameValidationDataProvider() {
        return [
            // Valid input
            ["test"],
            ["123456"],
            ["test_1"],
            ["!name"],
            ["hello!"],
            ["world.wide"],
            ["a b c"],
            ["_test_"]
        ];
    }

    public function testEmptyEmail() {
        $this->userService->register("test", "", "Password123", "Password123");
        $this->assertArrayHasKey("email", $this->validator->getErrors());
    }

     /**
     * @dataProvider EmailValidationDataProvider
     */
    public function testInvalidEmail($email) {
        $this->userService->register("TestUser", $email, "Password123", "Password123");
        $this->assertArrayHasKey("email", $this->validator->getErrors(), $email ." is a valid email");
    }
    public function EmailValidationDataProvider() {
        return [
            // Valid input
            ["invalid-mail"],
            ["test"],
            ["mail.com"],
            ["test@test"],
            [".email@example.com"],
            ["email.@example.com"],
            ["email..email@example.com"],
            ["email@111.222.333.44444"],
            ["email@example..com"],
            ["email@example.name"]
        ];
    }

    public function testEmptyPassword() {
        $this->userService->register("test", "test@test.com", "", "Password123");
        $this->assertArrayHasKey("password", $this->validator->getErrors());
    }

    public function testShortPassword() {
        $this->userService->register("TestUser", "test@test.com", "123", "123");
        $this->assertArrayHasKey("password", $this->validator->getErrors());
    }

    public function testEmptyConfirmPassword() {
        $this->userService->register("test", "test@test.com", "Password123", "");
        $this->assertArrayHasKey("confirmPassword", $this->validator->getErrors());
    }

    public function testPasswordMismatch() {
        $this->userService->register("TestUser", "test@test.com", "Password123", "WrongPassword");
        $this->assertArrayHasKey("confirmPassword", $this->validator->getErrors());
    }

    /**
     * @dataProvider registrationDataProvider
     */
    public function testValidRegistration($name, $email, $password, $confirmPassword, $expected) {
        // Simulate that email is NOT taken
        $this->repoMock->method('isUsernameTaken')->willReturn(false);

        // Simulate successful user saving
        $this->repoMock->method('register')->willReturn(true);

        $this->assertEquals($expected, $this->userService->register($name, $email, $password, $confirmPassword));

    }

    public function registrationDataProvider() {
        return [
            ["Test User", "test@test.com", "password123", "password123", false], //invalid username
            ["testuser", "invalid-email", "password123", "password123", false], //invalid email
            ["TestUser", "test@test.com", "password123", "password456", false], //password mismatch
            ["TestUser", "test@test.com", "1234", "1234", false],               //password invalid length
            ["TestUser", "test@test.com", "password123", "password123", true],  //valid inputs
        ];
    }


    /**
     * @dataProvider loginDataProvider
     */
    public function testValidLogin($name, $password, $expected) {
    
        // Simulate successful user saving
        $this->repoMock->method('login')->willReturn(true);

        $this->assertEquals($expected, $this->userService->login($name, $password));

    }

    public function loginDataProvider() {
        return [
            ["",  "password123", false], //invalid username
            ["testuser", "", false], //invalid email
            ["", "", false], //invalid email
            ["TestUser",  "password123", true]  //valid inputs
        ];
    }

    
        
}
?>
