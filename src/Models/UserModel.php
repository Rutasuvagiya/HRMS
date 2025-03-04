<?php
namespace HRMS\Models;

use HRMS\Database\DBConnection;
use PDO;

class UserModel
{
    private $dbConnection;

    public function __construct(PDO $pdo =null)
    {
        $this->dbConnection =  $pdo != null ?:DBConnection::getInstance();
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
       
        $statement = $this->dbConnection->prepare("SELECT password FROM users WHERE username = :username");
        $statement->execute(['username' => $username]);
        $user = $statement->fetch();
      
        return $user && password_verify($password, $user['password']);
    }

    public function isUsernameTaken($username) {
        $query = "SELECT id FROM users WHERE username = :username";
        $statement = $this->dbConnection->prepare($query);
        $statement->bindParam(":username", $username);
        $statement->execute();
       // $statement->debugDumpParams();
       
        return $statement->rowCount() > 0 ? true:false;
    }
}
?>
