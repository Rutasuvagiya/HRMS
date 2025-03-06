<?php

namespace HRMS\services;

use HRMS\Core\Validator;
use HRMS\Core\Notifier;
use HRMS\Core\Session;


class UserService{

    private Validator $validator;
    private $userModel;
    private $packageModel;
    private $notifier;
    private $errors = [];
    private $session;

    public function __construct( $userModel, $packageModel) {
        $this->userModel =$userModel;
        $this->packageModel =$packageModel;
        $this->validator = new Validator();
        $this->notifier = Notifier::getInstance();
        $this->session = Session::getInstance();
    }

    public function register($username, $email, $password, $confirmPassword) {
       
        $this->validator->validateRequiredFields(['username'=>$username, 'password'=>$password, 'email'=>$email, 'confirmPassword'=>$confirmPassword]);
        $this->validator->validateName($username, 'username');
        $this->validator->validateEmail($email, 'email');
        $this->validator->validatePassword($password, $confirmPassword, 'password', 'confirmPassword');
        $this->validator->isUsernameUnique($this->userModel->isUsernameTaken($username), 'username');

        if (empty($this->validator->getErrors())) {
        
            if ($this->userModel->register($username, $password, $email)) {
                $this->errors['generalMessage'] = "User registered successfully! Please login.";
                return true;
            } else {
                $this->errors['generalMessage'] = "Registration failed!";
                return false;
            }
           
        }else {
            $error = $this->validator->getErrors(); // Display errors
            $this->errors['error'] = $error;
            return false;
        }
    }

    public function login($username, $password)
    {
        $this->validator->validateRequiredFields(['username'=>$username, 'password'=>$password]);
        if (empty($this->validator->getErrors())) {
            if ($this->userModel->login($username, $password)) {
                if($this->session->get('role')=='patient'){
                    $this->notifier->addNotification("Welcome to the system!");

                    $count = $this->packageModel->getExpiringPackages();
                    $message = '';
                   if($count >0):
                       $message =  "Your package is expiring in next $count days.";
                   elseif($count === 0):
                       $message =  "Your package is expiring today.";
                   elseif($count < 0):
                       $message =  "Your package is expired.";
                   endif;
                   $this->notifier->addNotification($message);
                }
                
                return true;
            }
            else
            {
                $this->errors['generalMessage'] = 'Invalid credentials!';
                return false;
            }
        }

    }

    public function getErrors()
    {
        return  $this->errors;
    }


}