<?php
namespace HRMS\Tests;

use HRMS\Models\AdminModel;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

class AdminModelTest extends TestCase
{
    private $mockDb;
    private $mockStmt;
    private $adminModel;

    protected function setUp(): void
    {
        /// Create PDO and Statement Mocks
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStatement = $this->createMock(PDOStatement::class);

        // Mock the prepare method to return the statement mock
        $this->mockPDO->method('prepare')->willReturn($this->mockStatement);

        // Instantiate packageModel with the mock PDO
        $this->adminModel = new AdminModel($this->mockPDO);
    }

    public function testGetRecordLog()
    {
        $expectedResult = array(['id' => 1, 'user_id' => 1, 'record_id' => 10, 'changed_fields'=>'Age changed from "45" to "46"; ', '2025-03-04 21:50:39']);

        // Mock the `prepare` method to return mock statement
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->expects($this->once())
        ->method('fetchAll')
        ->with(PDO::FETCH_ASSOC)
        ->willReturn($expectedResult);

        $result = $this->adminModel->getRecordLog(1);
        $this->assertEquals($expectedResult, $result);
    }

    
}
