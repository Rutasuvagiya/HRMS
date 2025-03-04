<?php

namespace HRMS\services;

use HRMS\Validator;
use HRMS\Models\UserModel;

class UserService{
    private $userRepository;
    private Validator $validator;
    private UserModel $userModel;
    private $errors = [];

    public function __construct(UserModel $userModel, Validator $validator) {
        $this->userModel =$userModel;
        $this->validator = $validator;
    }

    public function register($username, $email, $password, $confirmPassword) {
       
        $this->validator->validateRequiredFields(['username'=>$username, 'password'=>$password, 'email'=>$email, 'confirmPassword'=>$confirmPassword]);
        $this->validator->validateName($username, 'username');
        $this->validator->validateEmail($email, 'email');
        $this->validator->validatePassword($password, $confirmPassword, 'password', 'confirmPassword');
        $this->validator->isUsernameUnique($this->userModel->isUsernameTaken($username), 'username');

        if (empty($this->validator->getErrors())) {
        
            if ($this->userModel->register($username, $password, $email)) {
                $error = "User registered successfully! Please login.";
                return true;
            } else {
                $error = "Registration failed!";
                return false;
            }
            $this->errors['generalMessage'] = $error;
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