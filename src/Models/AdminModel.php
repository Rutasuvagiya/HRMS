<?php
namespace HRMS\Models;

use HRMS\Database\DBConnection;
use HRMS\Session;
use PDO;

class AdminModel
{
    private $dbConnection;
    private $session;

    public function __construct(PDO $pdo =null)
    {
        $this->dbConnection =  $pdo != null ?:DBConnection::getInstance();
        $this->session = Session::getInstance();;
    }

    function getRecordLog($recordId)
    {
        $statement = $this->dbConnection->prepare("SELECT * FROM healthcare_logs WHERE record_id = :id ORDER BY updated_at");
        $statement->execute(['id' => $recordId]);
        return $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}