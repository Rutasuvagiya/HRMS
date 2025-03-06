<?php
namespace HRMS\Models;

use HRMS\Core\DBConnection;
use HRMS\Core\Session;
use PDO;

/**
 * Class AdminModel
 *
 * Handles user health record update logs.
 */
class AdminModel
{
    private $dbConnection;
    private $session;

    /**
     * Constructor to initialize the database connection and session
     * 
     * @param PDO $database PDO database connection object.
     * @return void
     */
    public function __construct(PDO $pdo =null)
    {
        $this->dbConnection =  $pdo != null ?:DBConnection::getInstance();
        $this->session = Session::getInstance();
    }

    /**
     * Fetch logs of health record changes done by front end users
     * 
     * @param int $recordId id of health record to get logs
     * @return array set of logs
     */
    function getRecordLog($recordId)
    {
        $statement = $this->dbConnection->prepare("SELECT * FROM healthcare_logs WHERE record_id = :id ORDER BY updated_at");
        $statement->execute(['id' => $recordId]);
        return $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}