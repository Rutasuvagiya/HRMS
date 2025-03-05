<?php
use PHPUnit\Framework\TestCase;
use App\Models\HealthRecordModel;
use App\Core\Database;

class HealthRecordTest extends TestCase {
    private HealthRecordModel $healthRecord;

    protected function setUp(): void {
        $config = [
            'host' => 'localhost',
            'dbname' => 'test_db',
            'username' => 'root',
            'password' => ''
        ];
        $database = Database::getInstance($config);
        $this->healthRecord = new HealthRecordModel($database);
    }

    public function testFetchAllRecords(): void {
        $records = $this->healthRecord->fetchAllRecords();
        $this->assertIsArray($records);
    }

    public function testFetchRecordById(): void {
        $record = $this->healthRecord->fetchRecordById(1);
        $this->assertIsArray($record);
    }

    public function testUpdateRecord(): void {
        $result = $this->healthRecord->updateRecord(1, "John Doe", 30, "Updated Diagnosis");
        $this->assertTrue($result);
    }

    public function testSaveRecordSuccess() {
        $this->stmtMock->method('execute')->willReturn(true);

        $data = [
            'name' => 'John Doe',
            'age' => 30,
            'gender' => 'Male',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'diagnosis' => 'Flu'
        ];
        $result = $this->model->saveRecord($data, "uploads/test.pdf");

        $this->assertTrue($result);
    }

    public function testSaveRecordFailure() {
        $this->stmtMock->method('execute')->willReturn(false);

        $data = [
            'name' => 'Jane Doe',
            'age' => 28,
            'gender' => 'Female',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
            'diagnosis' => 'Cold'
        ];
        $result = $this->model->saveRecord($data, "uploads/test.pdf");

        $this->assertFalse($result);
    }
}
