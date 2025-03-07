<?php
namespace HRMS\Tests;

use HRMS\Controllers\PackageController;
use HRMS\Services\PackageService;
use PHPUnit\Framework\TestCase;
use HRMS\Core\Session;

class PackageControllerTest extends TestCase
{
    private $packageServiceMock;
    private $sessionMock;
    private $controller;

    protected function setUp(): void
    {
        /// Mocking Session Class
        $this->sessionMock = $this->createMock(Session::class);


        // Define the expected return value for 'get' method
        $this->sessionMock->method('get')
        ->with('userID')
        ->willReturn('1');

        $this->sessionMock->method('checkSession')
        ->willReturn('true');

        // Mock PackageService
        $this->packageServiceMock = $this->createMock(PackageService::class);

        // Instantiate controller with mocked service
        $this->controller = $this->getMockBuilder(PackageController::class)
            ->onlyMethods(['render']) // Prevent `render()` execution
            ->setConstructorArgs([$this->sessionMock]) // Pass constructor arguments
            ->getMock();
        
            // Expect the render method to be called once but not executed
        $this->controller->expects($this->once())
        ->method('render')
        ->willReturn(true);
    }

    public function testAddPackageSuccess()
    {
        // Expect createPackage to be called once
        $this->packageServiceMock->method('savePackage')->willReturn(false);
         
        $this->assertFalse($this->controller->savePackage());
       
    }

    public function testUpgradePackage()
    {
         // Expect createPackage to be called once
         $this->packageServiceMock->method('upgradePackage')->willReturn(false);
         
         $this->assertFalse($this->controller->upgradePackage(1));
    }

   
}
