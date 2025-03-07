<?php

use PHPUnit\Framework\TestCase;
use HRMS\Controllers\HealthRecordController;
use HRMS\Services\HealthRecordService;
use HRMS\Models\HealthRecordModel;
use HRMS\Core\Session;

class HealthRecordControllerTest extends TestCase
{
    private $controller;
    private $healthRecordServiceMock;
    private $healthRecordModelMock;
    private $sessionMock;

    protected function setUp(): void
    {
        /// Mocking Session Class
        $this->sessionMock = $this->createMock(Session::class);

        // Mock dependencies
        $this->healthRecordModelMock = $this->createMock(HealthRecordModel::class);

        // Define the expected return value for 'get' method
        $this->sessionMock->method('get')
        ->with('userID')
        ->willReturn('1');

        $this->sessionMock->method('checkSession')
        ->willReturn('true');
       
        // Mock dependencies
        $this->healthRecordServiceMock = $this->createMock(HealthRecordService::class);

        // Create a partial mock for UserController
        $this->controller = $this->getMockBuilder(HealthRecordController::class)
            ->onlyMethods(['render']) // Prevent `render()` execution
            ->setConstructorArgs([$this->sessionMock]) // Pass constructor arguments
            ->getMock();

        // Expect the render method to be called once but not executed
        $this->controller->expects($this->once())
            ->method('render')
            ->willReturn(true);
    }

    public function testSubmitHealthRecord()
    {
        $this->healthRecordServiceMock->method('submitHealthRecord')->willReturn(false);

        $_FILES['attachment'] = ['name'=>''];

        // Call the login method (which calls render internally)
        $result =  $this->controller->submitHealthRecord(1,'Test Patient', 12, 'Male', 'Test allergies', 'Test Medication', $_FILES['attachment']);
        $this->assertFalse($result);
    }

    function testUserWiseRecords()
    {
        $this->assertTrue($this->controller->getUserWiseHealthRecords());
    }

    function testUpdateRecordDetailsSuccess()
    {

        $this->healthRecordModelMock->method('getHealthRecordByID')
            ->willReturn(array());

        // Set GET parameters
        $_GET['id'] = 1;

        $result = $this->controller->editRecord();
        $this->assertTrue($result);

        // Clean up after test
        unset($_GET['id']);
    }
    function testUpdateRecordDetailsFail()
    {

        $this->healthRecordModelMock->method('getHealthRecordByID')
            ->willReturn(array());

        $result = $this->controller->editRecord();
        $this->assertFalse($result);
    }
}
