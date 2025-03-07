<?php
use PHPUnit\Framework\TestCase;
use HRMS\Models\HealthRecordModel;
use HRMS\Core\Session;


class HealthRecordModelTest extends TestCase
{
    private $mockPDO;
    private $mockStatement;
    private $healthRecordModel;
    private $sessionMock;

    protected function setUp(): void
    {

         /// Create PDO and Statement Mocks
         $this->mockPDO = $this->createMock(PDO::class);
         $this->mockStatement = $this->createMock(PDOStatement::class);
 
         // Mock the prepare method to return the statement mock
         $this->mockPDO->method('prepare')->willReturn($this->mockStatement);
 
         

         $this->sessionMock = $this->createMock(Session::class);

         // Define the expected return value for 'get' method
            $this->sessionMock->method('get')
            ->with('userID')
            ->willReturn(1);

            // Instantiate UserModel with the mock PDO
            $this->healthRecordModel = new HealthRecordModel($this->mockPDO);
            }

    public function testAddHealthRecord()
    {
        $this->mockStatement->method('execute')->willReturn(true);
       
        $result = $this->healthRecordModel->addHealthRecord('Test', 11,'Male', 'test', 'testt','filepath');
        $this->assertTrue($result);
    }

    public function testUpdateHealthRecord()
    {
        $this->mockStatement->method('execute')->willReturn(true);
       
        $result = $this->healthRecordModel->updateHealthRecord(2, 'patient1', 12, 'Male', 'Test allergies', 'Test Medication', 'filepath');
        $this->assertTrue($result);
    }

    public function testGetHealthRecordById()
    {
        $expectedRecord = [
            'id'         => 1,
            'patient_id' => 'Test User',
            'age'  => 12,
            'gender'  => 'Male',
            'allergies'  => 'Rest and hydration',
            'medications'  => 'test',
            'attachment'  => 'filepath'
        ];

        $this->mockStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedRecord);

        $result = $this->healthRecordModel->getHealthRecordByID(1);
        $this->assertEquals($expectedRecord, $result);
    }

    public function testUserWiseHealthRecords()
    {
        $expectedRecord = [
            'id'         => 1,
            'patient_id' => 'Test User',
            'age'  => 12,
            'gender'  => 'Male',
            'allergies'  => 'Rest and hydration',
            'medications'  => 'test',
            'attachment'  => 'filepath'
        ];

        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedRecord);

        $result = $this->healthRecordModel->getUserWiseHealthRecords(1);
        $this->assertEquals($expectedRecord, $result);
    }

    public function testFetchAllRecords()
    {
        $expectedRecord = array(
            ['id'         => 1,
            'patient_id' => 'Test User',
            'age'  => 12,
            'gender'  => 'Male',
            'allergies'  => 'Rest and hydration',
            'medications'  => 'test',
            'attachment'  => 'filepath'],
            ['id'         => 2,
            'patient_id' => 'Test2 User',
            'age'  => 33,
            'gender'  => 'Female',
            'allergies'  => 'Fever',
            'medications'  => 'Paracetamol',
            'attachment'  => 'filepath']
        );

        $this->mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedRecord);

        $result = $this->healthRecordModel->getAllRecords(0);
        $this->assertEquals($expectedRecord, $result);
    }
}
