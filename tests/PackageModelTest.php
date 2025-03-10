<?php

namespace HRMS\Tests;

use HRMS\Models\PackageModel;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class PackageModelTest extends TestCase
{
    private $mockDb;
    private $mockStmt;
    private $packageModel;
    protected function setUp(): void
    {
        /// Create PDO and Statement Mocks
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);
    // Mock the prepare method to return the statement mock
        $this->mockPDO->method('prepare')->willReturn($this->mockStatement);
    // Instantiate packageModel with the mock PDO
        $this->packageModel = new PackageModel($this->mockPDO);
    }

    public function testAddPackageSuccess()
    {

        // Mock the `prepare` method to return mock statement
        $this->mockStatement->method('execute')->willReturn(true);
        $result = $this->packageModel->savePackage('Premium', 500, 30);
        $this->assertTrue($result);
    }

    public function testGetPackageById()
    {
        $expectedResult = array(['id' => 1, 'name' => 'Basic', 'price' => 200, 'validity' => 10]);
// Mock the `prepare` method to return mock statement
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedResult);
        $result = $this->packageModel->getPackageList(1);
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetExpiringPackage()
    {
        $package['left_days'] = 4;
// Mock the `prepare` method to return mock statement
        $this->mockStatement->method('rowCount')->willReturn(1);
        $this->mockStatement->method('fetch')
        ->willReturn($package);
        $this->mockStatement->method('execute')->willReturn(true);
        $result = $this->packageModel->getExpiringPackages(1);
        $this->assertGreaterThan(0, $result);
    }

    public function testGetExpiringPackageFail()
    {

        // Mock the `prepare` method to return mock statement
        $this->mockStatement->method('rowCount')->willReturn(0);
        $this->mockStatement->method('execute')->willReturn(true);
        $result = $this->packageModel->getExpiringPackages(1);
        $this->assertEquals('', $result);
    }

    public function testGetPackagelistForUser()
    {
        $expectedResult = array(['id' => 1, 'name' => 'Basic', 'price' => 200, 'validity' => 10]);
// Mock the `prepare` method to return mock statement
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedResult);
        $result = $this->packageModel->getUserPackageList();
        $this->assertEquals($expectedResult, $result);
    }

    public function testUpgradePackage()
    {
        $this->mockStatement->method('execute')->willReturn(true);
        $result = $this->packageModel->upgradePackage(1, 2, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), date('Y-m-d', strtotime("+5 day")));
        $this->assertTrue($result);
    }
}
