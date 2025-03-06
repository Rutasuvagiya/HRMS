<?php

namespace HRMS\Models;

use HRMS\Database\DBConnection;
use HRMS\Core\Session;
use PDO;
use PDOException;

class HealthRecordModel {
    private $db;
    private $session;
    private $dbConnection;

    public function __construct(PDO $pdo =null)
    {
        $this->dbConnection =  $pdo != null ?:DBConnection::getInstance();
        $this->session = Session::getInstance();
    }

    public function addHealthRecord($patient_name, $age, $gender, $allergies, $medications, $filePath) {
        try {
            $query = "INSERT INTO healthcare_records (patient_name, age, gender, allergies, medications, attachment,created_by) 
                      VALUES (:name, :age, :gender, :allergies, :medications, :attachment, :created_by)";

            $statement = $this->dbConnection->prepare($query);
            $statement->bindParam(":name", $patient_name);
            $statement->bindParam(":age", $age);
            $statement->bindParam(":gender", $gender);
            $statement->bindParam(":allergies", $allergies);
            $statement->bindParam(":medications", $medications);
            $statement->bindParam(":attachment", $filePath);
            $statement->bindParam(":created_by", $this->session->get('userID'));
           
             return $statement->execute();
            //echo $statement->debugDumpParams();
            
        } catch (PDOException $e) {
            return false;
        }
    }
    public function updateHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $filePath) {
        try {
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
           
            return false;
        }
    }

    public function getUserWiseHealthRecords($userId=''): array {
       
        $userId = !empty($userId) ?$userId: $this->session->get('userID');
        $statement = $this->dbConnection->prepare("SELECT * FROM healthcare_records WHERE created_by = :username ORDER BY created_at desc");
        $statement->execute(['username' => $userId]);
        return $records = $statement->fetchAll(PDO::FETCH_ASSOC);
      
    }
    public function getHealthRecordByID($recordId=''): array {
       
        $userId = !empty($userId) ?$userId: $this->session->get('userID');
        $statement = $this->dbConnection->prepare("SELECT * FROM healthcare_records WHERE id = :id ORDER BY created_at desc");
        $statement->execute(['id' => $recordId]);
        return $records = $statement->fetch(PDO::FETCH_ASSOC);
      
    }

    public function getAllRecords($count):array{
        $limit = $count > 0 ? " LIMIT $count":'';
        $statement = $this->dbConnection->query("SELECT * FROM healthcare_records ORDER BY created_at desc $limit");
        return $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        
    }
}
