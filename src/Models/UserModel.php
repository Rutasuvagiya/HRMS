<?php
namespace HRMS\Models;

use HRMS\Core\DBConnection;
use HRMS\Core\Session;
use HRMS\Core\UserRoleStrategy;
use HRMS\Core\AdminRole;
use HRMS\Core\PatientRole;
use PDO;

/**
 * Class UserModel
 *
 * Handles user authentication and registration in system.
 */
class UserModel
{
    private $dbConnection;
    private $roleStrategy;
    private $session;

    /**
     * Constructor to initialize the database connection
     * 
     * @param PDO $database PDO database connection object.
     * @return void
     */
    public function __construct(PDO $pdo =null)
    {
        $this->dbConnection =  $pdo != null ?:DBConnection::getInstance();
    }


    /**
     * Registers a new user in the system.
     * 
     * @param string $username unique username of the user.
     * @param string $password Plain-text password.
     * @param string $email Email address.
     * @return bool|string True if successful, error message otherwise.
     */
    public function register(string $username, string $password, $email): bool {
        try {
            // Verify password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user
            $statement = $this->dbConnection->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
            return $statement->execute(['username' => $username, 'password' => $hashedPassword, 'email' => $email]);
    
        }
        catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

     /**
     * Authenticates a user and starts a session.
     * 
     * @param string $username Email address of the user.
     * @param string $password Plain-text password (compared with hashed password).
     * @return bool|string True if login successful, error message otherwise.
     */
    public function login(string $username, string $password): bool {
        try {
             // Fetch user details
            $statement = $this->dbConnection->prepare("SELECT id,role, password FROM users WHERE username = :username");
            $statement->execute(['username' => $username]);
            $user = $statement->fetch();
        
            // Verify password
            if($user && password_verify($password, $user['password']))
            {
                //Start session and store user data in session
                $this->session = Session::getInstance();
                $this->session->set('userID', $user['id']);
                $this->session->set('role', $user['role']);

                //set user role using strategy pattern to get role wise dashboard 
                $this->setRole($user['role']);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Check if username is unique or not
     * 
     * @param string $username user input name
     * @return bool If username exists return true else false
     */
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
