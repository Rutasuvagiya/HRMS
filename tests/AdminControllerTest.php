<?php

use PHPUnit\Framework\TestCase;
use HRMS\Controllers\AdminController;
use HRMS\Models\HealthRecordModel;
use HRMS\Models\packageModel;
use HRMS\Services\AdminService;
use HRMS\Models\AdminModel;
use HRMS\Core\Session;

class AdminControllerTest extends TestCase
{
    private $controller;
    private $adminServiceMock;
    private $adminModelMock;
    private $healthRecordModel;
    private $packageModel;
    private $sessionMock;

    protected function setUp(): void
    {
        /// Mocking Session Class
        $this->sessionMock = $this->createMock(Session::class);

        // Mock dependencies
        $this->adminModelMock = $this->createMock(AdminModel::class);
        $this->healthRecordModel = $this->createMock(HealthRecordModel::class);
        $this->packageModel = $this->createMock(PackageModel::class);

        // Define the expected return value for 'get' method
        $this->sessionMock->method('get')
        ->with('userID')
        ->willReturn('1');

        $this->sessionMock->method('checkSession')
        ->willReturn('true');

        // Create a partial mock for UserController
        $this->controller = $this->getMockBuilder(AdminController::class)
            ->onlyMethods(['render']) // Prevent `render()` execution
            ->setConstructorArgs([$this->sessionMock]) // Pass constructor arguments
            ->getMock();

        // Expect the render method to be called once but not executed
        $this->controller->expects($this->once())
            ->method('render')
            ->willReturn(true);
    }

    public function testGetAdminDashboardData()
    {
        $this->healthRecordModel->method('getAllRecords')->willReturn(array());
        $this->packageModel->method('getPackageList')->willReturn(true);

        $result =  $this->controller->getAdminDashboardData();
        $this->assertTrue($result);
    }

    function testGetPackages()
    {
        $this->packageModel->method('getPackageList')->willReturn(true);
        $this->assertTrue($this->controller->getPackages());
    }

    function testGetAllPatientRecords()
    {
        $this->healthRecordModel->method('getAllRecords')->willReturn(array());

        $this->assertTrue($this->controller->getAllPatientRecords());
    }
}
