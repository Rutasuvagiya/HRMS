<?php

namespace HRMS\Models;

use HRMS\Core\DBConnection;
use HRMS\Core\Session;
use PDO;

/**
 * Class PackageModel
 *
 * Handles package management in system
 */
class PackageModel
{
    private $dbConnection;
    private $session;
/**
     * Constructor to initialize the database connection and session
     *
     * @param PDO $database PDO database connection object.
     * @return void
     */
    public function __construct(PDO $pdo = null)
    {
        $this->dbConnection =  $pdo != null ? $pdo : DBConnection::getInstance();
        $this->session = Session::getInstance();
    }

    /**
     * Get list of all packages or for given id
     *
     * @param int|null $id package id.
     * @return array list of packages
     */
    public function getPackageList($id = null)
    {
        try {
//Check if id is given then add where condition.
            $where = is_null($id) ? '' : " where id=$id";
            $query = "SELECT * FROM packages $where";
            $statement = $this->dbConnection->prepare($query);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * insert new package
     *
     * @param string $name name of package
     * @param float $price price of package
     * @param int $validity number of days
     * @return bool|string True if successful, error message otherwise.
     */
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
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * check if active package is expiring in next 5 days then return count of days
     *
     * @return int|string count of days left|empty string
     */
    public function getExpiringPackages()
    {
        $userId = $this->session->get('userID');
        try {
            $query = "SELECT DATEDIFF(end_date, CURDATE()) as left_days FROM user_package WHERE end_date <= DATE_ADD(CURDATE(), INTERVAL 5 DAY) AND status = '1' AND user_id= :id";
            $statement = $this->dbConnection->prepare($query);
            $statement->bindParam(':id', $userId);
            $statement->execute();
            $package = $statement->fetch();
            return $statement->rowCount() > 0 ? $package['left_days'] : '';
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * get package details which is active for currently login user
     *
     * @return array package details
     */
    public function getUserPackageList()
    {
        try {
            $userId = $this->session->get('userID');
            $query = "SELECT * FROM user_package join packages on packages.id = user_package.package_id WHERE user_package.user_id = :user_id and user_package.status=1";
            $statement = $this->dbConnection->prepare($query);
            $statement->bindParam(":user_id", $userId);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * update existing package for given user
     *
     * @param int $userId user id for which package needs to be updated
     * @param int $packageId package id
     * @param date $startDate package start date
     * @param date $expiryDate package end date
     */
    public function upgradePackage($userId, $packageId, $startDate, $expiryDate)
    {
        try {
            $query = "UPDATE user_package SET start_date =  :start_date, end_date = :end_date, package_id = :package_id, status = 1 WHERE user_id = :user_id";
            $statement = $this->dbConnection->prepare($query);
            $statement->bindParam(":start_date", $startDate);
            $statement->bindParam(":end_date", $expiryDate);
            $statement->bindParam(":package_id", $packageId);
            $statement->bindParam(":user_id", $userId);
            return $statement->execute();
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
