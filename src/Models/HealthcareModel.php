<?php

namespace HRMS\Models;

use HRMS\Database\DBConnection;
use PDO;
use PDOException;

class HealthcareModel {
    private $db;

    private $dbConnection;

    public function __construct(PDO $pdo =null)
    {
        $this->dbConnection =  $pdo != null ?:DBConnection::getInstance();
    }

    public function saveRecord($data, $filePath) {
        try {
            $query = "INSERT INTO healthcare_records (patient_name, age, gender, phone, diagnosis, attachment) 
                      VALUES (:name, :age, :gender, :email, :phone, :diagnosis, :attachment)";

            $stmt = $this->dbConnection->prepare($query);
            $stmt->bindParam(":name", $data['name']);
            $stmt->bindParam(":age", $data['age']);
            $stmt->bindParam(":gender", $data['gender']);
            $stmt->bindParam(":phone", $data['phone']);
            $stmt->bindParam(":diagnosis", $data['diagnosis']);
            $stmt->bindParam(":attachment", $filePath);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
