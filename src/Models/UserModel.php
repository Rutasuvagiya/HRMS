<?php
namespace HRMS\Models;

use HRMS\Database\DBConnection;
use HRMS\Core\Session;
use HRMS\Core\UserRoleStrategy;
use HRMS\Core\AdminRole;
use HRMS\Core\PatientRole;
use PDO;

class UserModel
{
    private $dbConnection;
    private $roleStrategy;
    private $session;

    public function __construct(PDO $pdo =null)
    {
        $this->dbConnection =  $pdo != null ?:DBConnection::getInstance();
        $this->session = Session::getInstance();
    }

   
    public function read($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $this->dbConnection->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function register(string $username, string $password, $email): bool {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $statement = $this->dbConnection->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        return $statement->execute(['username' => $username, 'password' => $hashedPassword, 'email' => $email]);
    }

    public function login(string $username, string $password): bool {
       
        $statement = $this->dbConnection->prepare("SELECT id,role, password FROM users WHERE username = :username");
        $statement->execute(['username' => $username]);
        $user = $statement->fetch();
      
        if($user && password_verify($password, $user['password']))
        {
            $this->session->set('userID', $user['id']);
            $this->session->set('role', $user['role']);
            $this->setRole($user['role']);
            return true;
        }
        return false;
    }

    public function isUsernameTaken($username) {
        $query = "SELECT id FROM users WHERE username = :username";
        $statement = $this->dbConnection->prepare($query);
        $statement->bindParam(":username", $username);
        $statement->execute();
       // $statement->debugDumpParams();
       
        return $statement->rowCount() > 0 ? true:false;
    }

     /**
     * Sets the user's role strategy based on their role.
     * 
     * @param string $role User's role from the database.
     */
    public function setRole(string $role) {
        switch ($role) {
            case 'admin':
                $this->roleStrategy = new AdminRole();
                break;
            case 'patient':
                $this->roleStrategy = new PatientRole();
                break;
            default:
                throw new \Exception("Invalid role.");
        }
    }

    /**
     * Gets the dashboard file for the assigned role.
     * 
     * @return string Dashboard file name.
     */
    public function getDashboard() {
        return $this->roleStrategy->getDashboard();
    }
}
?>
