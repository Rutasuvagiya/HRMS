<?php

namespace HRMS\services;

use HRMS\Core\Validator;
use HRMS\Core\Notifier;
use HRMS\Core\Session;
use HRMS\Factories\ModelFactory;

/**
 * Class UserService
 *
 * Provides business logic for user authentication (login & registration) in the Health Record Management System.
 */
class UserService{

    private Validator $validator;
    private $userModel;
    private $packageModel;
    private $notifier;
    private $errors = [];
    private $session;

    /**
     * Constructor to initialize the 
     * User model - login and registrion, 
     * package model - get expiring package details, 
     * notifier - add push notification, 
     * validator - validate inputs,
     * Session - session management
     * 
     * @param UserModel $userModel The User model instance.
     */
    public function __construct( $userModel) {
        $this->userModel =$userModel;
        $this->validator = new Validator();
        $this->notifier = Notifier::getInstance();
        $this->session = Session::getInstance();
        $this->packageModel= ModelFactory::create('packageModel');
    }

    /**
     * Register new user after validating user inputs
     * 
     * @param string $username unique username
     * @param string $email email address
     * @param string $password 
     * @param string $confirmPassword 
     * @return bool|string True if register successful, error message otherwise.
     */
    public function register($username, $email, $password, $confirmPassword) {
       
        //Check all the required fields are not empty
        $this->validator->validateRequiredFields(['username'=>$username, 'password'=>$password, 'email'=>$email, 'confirmPassword'=>$confirmPassword]);
        //Check name is valid
        $this->validator->validateName($username, 'username');
        //check email address is valid
        $this->validator->validateEmail($email, 'email');
        //check password is valid and both passwords matches
        $this->validator->validatePassword($password, $confirmPassword, 'password', 'confirmPassword');
        //check if username is unique or not
        $this->validator->isUsernameUnique($this->userModel->isUsernameTaken($username), 'username');
        try{
            if (empty($this->validator->getErrors())) {
                //insert record into database to register 
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
        }  catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * login existing user after validating user inputs
     * 
     * @param string $username 
     * @param string $password 
     * @return bool|string True if login successful, error message otherwise.
     */
    public function login($username, $password)
    {
        //check if both username and password are not empty
        $this->validator->validateRequiredFields(['username'=>$username, 'password'=>$password]);
        if (empty($this->validator->getErrors())) {
            try{
                
                //Check from database if username and password are valid
                if ($this->userModel->login($username, $password)) {

                    //If credentials are valid and logged in user is patient
                    if($this->session->get('role')=='patient'){

                        //Add push notification to patient
                        $this->notifier->addNotification("Welcome to the system!");

                        //Check active package status
                        $count = $this->packageModel->getExpiringPackages();
                        $message = '';

                        //Insert message in push notification if package is expiring in next 5 days
                        if($count >0):
                            $message =  "Your package is expiring in next $count days.";
                        elseif(is_numeric($count) && $count == 0):
                            echo $message =  "Your package is expiring today.";
                        elseif($count < 0):
                            $message =  "Your package is expired.";
                        endif;

                        //Add package expiry message in push notification
                        $this->notifier->addNotification($message);
                    
                    }
                    
                    return true;
                }
                else
                {
                    $this->errors['generalMessage'] = 'Invalid credentials!';
                    return false;
                }
             }  catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    /**
     * return all the errors 
     * 
     * @return array list of array
     */
    public function getErrors()
    {
        return  $this->errors;
    }


}