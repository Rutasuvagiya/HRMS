<?php

namespace HRMS\Models;

use HRMS\Core\DBConnection;
use HRMS\Core\Session;
use PDO;
use PDOException;

/**
 * Class HealthRecordModel
 *
 * Handles health record management in system
 */
class HealthRecordModel {
    private $db;
    private $session;
    private $dbConnection;

    /**
     * Constructor to initialize the database connection and session
     * 
     * @param PDO $database PDO database connection object.
     * @return void
     */
    public function __construct(PDO $pdo =null)
    {
        $this->dbConnection =  $pdo != null ?$pdo:DBConnection::getInstance();
        $this->session =  Session::getInstance();
    }

    /**
     * Adds a new health record for a patient.
     * 
     * @param string $patient_name Patient ID.
     * @param float $age patient age.
     * @param string $gender patient gender.
     * @param string $allergies allergies details.
     * @param string $medications Prescription details.
     * @param string $filePath File path of an attachment.
     * @return bool|string True if successful, error message otherwise.
     */
    public function addHealthRecord($patient_name, $age, $gender, $allergies, $medications, $filePath) {
        try {
            $query = "INSERT INTO healthcare_records (patient_name, age, gender, allergies, medications, attachment,created_by) 
                      VALUES (:name, :age, :gender, :allergies, :medications, :attachment, :created_by)";

            $userId = $this->session->get('userID');
            $statement = $this->dbConnection->prepare($query);
            $statement->bindParam(":name", $patient_name);
            $statement->bindParam(":age", $age);
            $statement->bindParam(":gender", $gender);
            $statement->bindParam(":allergies", $allergies);
            $statement->bindParam(":medications", $medications);
            $statement->bindParam(":attachment", $filePath);
            $statement->bindParam(":created_by", $userId);
           
             return $statement->execute();
            //echo $statement->debugDumpParams();
            
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Updates an existing health record.
     * 
     * @param int $id Health Record ID.
     * @param string $patient_name patient name
     * @param string $age Updated diagnosis.
     * @param string $allergies allergies details.
     * @param string $medications Prescription details.
     * @param string $filePath File path of an attachment.
     * @return bool|string True if successful, error message otherwise.
     */
    public function updateHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $filePath) {
        try {
            //if file is uploaded then update else skip attachment in update query
            $attachment = $filePath != ''? ", attachment =:attachment" : '';
            $query = "UPDATE healthcare_records SET patient_name = :name, age =:age, gender=:gender, allergies =:allergies, medications=:medications $attachment WHERE id=:id";

            $statement = $this->dbConnection->prepare($query);
            $statement->bindParam(":name", $patient_name);
            $statement->bindParam(":age", $age);
            $statement->bindParam(":gender", $gender);
            $statement->bindParam(":allergies", $allergies);
            $statement->bindParam(":medications", $medications);
            if($filePath != ''){ $statement->bindParam(":attachment", $filePath);}
            $statement->bindParam(":id", $id);
           
            return $statement->execute();
          // echo $statement->debugDumpParams();
           
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Retrieves health records by user ID
     * 
     * @param int $userId user ID
     * @return array|string Health record details if found, error message otherwise.
     */
    public function getUserWiseHealthRecords($userId=''): array {
        try {
            //check if userId is not set then get user id from session 
            $userId = !empty($userId) ?$userId: $this->session->get('userID');
            $statement = $this->dbConnection->prepare("SELECT * FROM healthcare_records WHERE created_by = :username ORDER BY created_at desc");
            $statement->execute(['username' => $userId]);
            return $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Retrieves a single health record by its ID.
     * 
     * @param int $recordId Health Record ID.
     * @return array|string Health record details if found, error message otherwise.
     */
    public function getHealthRecordByID($recordId=''): array {
        try {
            $userId = !empty($userId) ?$userId: $this->session->get('userID');
            $statement = $this->dbConnection->prepare("SELECT * FROM healthcare_records WHERE id = :id ORDER BY created_at desc");
            $statement->execute(['id' => $recordId]);
            return $records = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
      
    }

    /**
     * Retrieves all health records
     * 
     * @param int $count if value set then add limit in fetch records
     * @return array|string Health record details if found, error message otherwise.
     */
    public function getAllRecords($count):array{
        try {
            //If count >0 then set limit
            $limit = $count > 0 ? " LIMIT $count":'';
            $statement = $this->dbConnection->prepare("SELECT * FROM healthcare_records ORDER BY created_at desc $limit");
            $statement->execute();
            return $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
