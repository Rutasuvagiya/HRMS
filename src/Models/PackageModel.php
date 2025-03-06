<?php
namespace HRMS\Models;

use HRMS\Database\DBConnection;
use HRMS\Core\Session;
use PDO;

class PackageModel
{
    private $dbConnection;
    private $session;

    public function __construct(PDO $pdo =null)
    {
        $this->dbConnection =  $pdo != null ?:DBConnection::getInstance();
        $this->session = Session::getInstance();
    }

    public function getPackageList($id =null)
    {
        $where = is_null($id)?'':" where id=$id";
        $query = "SELECT * FROM packages $where";
        $statement = $this->dbConnection->query($query);
        
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function savePackage($name, $price, $validity)
    {
        try {
            $query = "INSERT INTO packages (name, price, validity) 
                      VALUES (:name, :price, :validity)";

            $statement = $this->dbConnection->prepare($query);
            $statement->bindParam(":name", $name);
            $statement->bindParam(":price", $price);
            $statement->bindParam(":validity", $validity);
           
            return $statement->execute();
           
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getExpiringPackages() {
         $userId = $this->session->get('userID');
         $query = "SELECT DATEDIFF(end_date, CURDATE()) as left_days FROM user_package WHERE end_date <= DATE_ADD(CURDATE(), INTERVAL 5 DAY) AND status = '1' AND user_id= :id";
        $statement = $this->dbConnection->prepare($query);
        $statement->bindParam(':id', $userId);
        $statement->execute();
        $package = $statement->fetch();
        return $statement->rowCount() > 0 ? $package['left_days']:'';
    }

    public function getUserPackageList()
    {
        $userId = $this->session->get('userID');
        $query = "SELECT * FROM user_package join packages on packages.id = user_package.package_id WHERE user_package.user_id = :user_id and user_package.status=1";
        $statement = $this->dbConnection->prepare($query);
        $statement->bindParam(":user_id", $userId);

        
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function upgradePackage($userId, $packageId, $startDate, $newExpiryDate) {
        $query = "UPDATE user_package SET start_date =  :start_date, end_date = :end_date, package_id = :package_id, status = 1 WHERE user_id = :user_id";
        $statement = $this->dbConnection->prepare($query);
        $statement->bindParam(":start_date", $startDate);
        $statement->bindParam(":end_date", $newExpiryDate);
        $statement->bindParam(":package_id", $packageId);
        $statement->bindParam(":user_id", $userId);
       
        return $statement->execute();
    }

}