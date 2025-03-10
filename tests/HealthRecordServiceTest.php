<?php

use PHPUnit\Framework\TestCase;
use HRMS\Services\HealthRecordService;
use HRMS\Models\HealthRecordModel;
use HRMS\Core\Session;

class HealthRecordServiceTest extends TestCase
{
    private $healthRecordService;
    private $healthRecordModelMock;
    private $sessionMock;

    protected function setUp(): void
    {
        // Mock dependencies
        $this->healthRecordModelMock = $this->createMock(HealthRecordModel::class);
        $this->sessionMock = $this->createMock(Session::class);

        // Instantiate service with mocked dependencies
        $this->healthRecordService = new HealthRecordService(
            $this->healthRecordModelMock
        );
    }

    /**
     * @dataProvider HealthRecords
     */
    public function testAddHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $attachment)
    {
        $this->healthRecordModelMock->expects($this->once())
            ->method('addHealthRecord')
            ->willReturn(true);

        $result = $this->healthRecordService->submitHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $attachment);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider HealthRecords
     */
    public function testAddHealthRecordInsertionFailed($id, $patient_name, $age, $gender, $allergies, $medications, $attachment)
    {
        $this->healthRecordModelMock->expects($this->once())
            ->method('addHealthRecord')
            ->willReturn(false);

        $result = $this->healthRecordService->submitHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $attachment);

        $this->assertFalse($result);
    }

    public function HealthRecords()
    {
        // Mock the uploaded file
        $_FILES['attachment'] = ['name' => ''];
        return [
            // Invalid input

            ['','patient1', 12, 'Male', 'Test allergies', 'Test Medication', $_FILES['attachment'] ],
            ['','patient2', 22, 'Female', 'Test allergies1', 'Test Medication1', $_FILES['attachment'] ],
        ];
    }

    public function testFileUpload()
    {
        // Create a temporary file for testing
        $tmpFile = tempnam(sys_get_temp_dir(), 'testfile');
        file_put_contents($tmpFile, 'Dummy content'); // Writing sample data

        // Simulate $_FILES array
         $_FILES['attachment'] = [
            'name'     => 'testfile.pdf',
            'type'     => 'application/pdf',
            'tmp_name' => $tmpFile,
            'error'    => 0,
            'size'     => filesize($tmpFile),
         ];

         $this->healthRecordModelMock
            ->method('addHealthRecord')
            ->willReturn(false);

         $result = $this->healthRecordService->submitHealthRecord(1, 'test', 12, 'Male', 'test', '', $_FILES['attachment']);

         $this->assertFalse($result);
    }



    public function testEmptyName()
    {
        $this->healthRecordService->submitHealthRecord("", "", 12, 'Male', 'Test allergies', 'Test Medication', $_FILES['attachment']);
        $this->assertArrayHasKey("patient_name", $this->getErrors());
    }

    public function testEmptyAge()
    {
        $this->healthRecordService->submitHealthRecord("", "test", "", 'Male', 'Test allergies', 'Test Medication', $_FILES['attachment']);
        $this->assertArrayHasKey("age", $this->getErrors());
    }

    public function testEmptyAllergies()
    {
        $this->healthRecordService->submitHealthRecord("", "test", 23, 'Male', '', 'Test Medication', $_FILES['attachment']);
        $this->assertArrayHasKey("allergies", $this->getErrors());
    }

    public function testValidAge()
    {
        $this->healthRecordService->submitHealthRecord("", "test", 'invalidAge', 'Male', 'Test allergies', 'Test Medication', $_FILES['attachment']);
        $this->assertArrayHasKey("age", $this->getErrors());
    }

    public function testValidGender()
    {
        $this->healthRecordService->submitHealthRecord("", "test", '12', 'Man', 'Test allergies', 'Test Medication', $_FILES['attachment']);
        $this->assertArrayHasKey("gender", $this->getErrors());
    }

    /**
     * @dataProvider UpdateHealthRecords
     */
    public function testUpdateHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $attachment)
    {
        $this->healthRecordModelMock
            ->method('updateHealthRecord')
            ->willReturn(true);

        $result = $this->healthRecordService->submitHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $attachment);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider UpdateHealthRecords
     */
    public function testUpdateHealthRecordFailure($id, $patient_name, $age, $gender, $allergies, $medications, $attachment)
    {
        $this->healthRecordModelMock
            ->method('updateHealthRecord')
            ->willReturn(false);

        $result = $this->healthRecordService->submitHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $attachment);

        $this->assertFalse($result);
    }


    public function UpdateHealthRecords()
    {
        // Mock the uploaded file
        $_FILES['attachment'] = ['name' => ''];
        return [
            // Invalid input

            [1,'patient1', 12, 'Male', 'Test allergies', 'Test Medication', $_FILES['attachment'] ],
            [2,'patient2', 22, 'Female', 'Test allergies1', 'Test Medication1', $_FILES['attachment'] ],
        ];
    }

    public function getErrors()
    {
        $errors = $this->healthRecordService->getErrors();
        return  $errors['error'];
    }
}
