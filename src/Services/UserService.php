<?php

namespace HRMS\services;

use HRMS\Validator;


class UserService{

    private Validator $validator;
    private $userModel;
    private $errors = [];

    public function __construct( $userModel) {
        $this->userModel =$userModel;
        $this->validator = new Validator();
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