<?php

namespace HRMS\Tests;

use HRMS\Services\PackageService;
use HRMS\Models\PackageModel;
use PHPUnit\Framework\TestCase;
use Exception;

class PackageServiceTest extends TestCase
{
    private $packageService;
    private $packageModelMock;
    protected function setUp(): void
    {
        // Create a mock for PackageModel
        $this->packageModelMock = $this->createMock(PackageModel::class);
    // Inject the mock into PackageService
        $this->packageService = new PackageService($this->packageModelMock);
    }

    public function testEmptyName()
    {
        $this->packageService->savePackage('', 10, 15);
        $this->assertArrayHasKey("name", $this->getErrors());
    }
    public function testEmptyPrice()
    {
        $this->packageService->savePackage('Gold', '', 15);
        $this->assertArrayHasKey("price", $this->getErrors());
    }
    public function testEmptyValidity()
    {
        $this->packageService->savePackage('Gold', 1000, '');
        $this->assertArrayHasKey("validity", $this->getErrors());
    }
    public function testInvalidValidity()
    {
        $this->packageService->savePackage('Gold', 1000, 'invalid');
        $this->assertArrayHasKey("validity", $this->getErrors());
    }
    public function testInvalidPrice()
    {
        $this->packageService->savePackage('Gold', 'invalid', 100);
        $this->assertArrayHasKey("price", $this->getErrors());
    }

    public function testSavePackageSuccess()
    {
        $this->packageModelMock->method('savePackage')->willReturn(true);
        $this->assertTrue($this->packageService->savePackage('Gold', 500, 30));
    }

    public function testSavePackageFailure()
    {
        $this->packageModelMock->method('savePackage')->willReturn(false);
        $this->assertFalse($this->packageService->savePackage('Gold', 500, 30));
    }

    public function testUpdatePackageSuccess()
    {
        $packages[] = array('validity' => 10);
        $this->packageModelMock->method('getPackageList')->willReturn($packages);
        $this->packageModelMock->method('upgradePackage')->willReturn(false);
        $this->assertFalse($this->packageService->upgradePackage(1, 2));
    }

    public function getErrors()
    {
        $errors = $this->packageService->getErrors();
        return  $errors['error'];
    }
}
