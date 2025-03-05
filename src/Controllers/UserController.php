<?php
namespace HRMS\Controllers;

use HRMS\Controller;
use HRMS\Services\UserService;
use HRMS\Models\ModelFactory;
use HRMS\Session;


class UserController  extends Controller {
    private UserService $user;
    private  $packageModel;
    private $session;

    public function __construct() {
        
        $this->user = new UserService(ModelFactory::create('UserModel'));
        
        $this->session = Session::getInstance();;
        
    }

    public function registerUser(): void {
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            
            $arrExpected = ['username', 'password', 'email', 'confirmPassword'];
           
            foreach($arrExpected as $value)
            {
                $$value = $_POST[$value]??'';
            }

            if($this->user->register($username, $email, $password, $confirmPassword)) {
               
                $message = "User registered successfully! Please login.";
                $this->render('login', ['generalMessage' => $message]);
            } else {
                $error = $this->user->getErrors();
                $this->render('register', $error);
            }
            
        }
       
    }

    public function register(): void{
        $this->render('register');
    }

    public function loginUser(): void {
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            $arrRequired = ['username', 'password'];
            $arrExpected = ['username', 'password'];
           
            foreach($arrExpected as $value)
            {
                $$value = $_POST[$value]??'';
            }

          
            if ($this->user->login($username, $password)) {
                
                if ($this->session->get('role') === 'admin') {
                    header("Location: adminDashboard");
                } else {
                    header("Location: dashboard");
                }
                exit;
            } else {
                $error = $this->user->getErrors();
                
                $this->render('login', $error);
            }
        }
        
    }
    public function login(): void{
      
        $this->render('login');
    }

    public function logout()
    {
        $this->session->destroy();
        $this->render('login', ['generalMessage' => 'Logout successfully.']);
    }
}
