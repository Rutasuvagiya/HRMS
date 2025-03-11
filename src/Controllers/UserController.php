<?php

namespace HRMS\Controllers;

use HRMS\Core\Controller;
use HRMS\Services\UserService;
use HRMS\Factories\ModelFactory;
use HRMS\Core\Session;
use Exception;


/**
 * Class UserController
 *
 * Handles user authentication and registration in the Health Record Management System.
 */
class UserController extends Controller
{
    private $service;
    private $model;
    private $session;

    /**
     * Constructor to initialize the User model using a factory.
     * and initialize User Service and session.
     */
    public function __construct($serviceMock = null)
    {
        
        $this->model = ModelFactory::create('UserModel');
        $this->service = !is_null($serviceMock) ? $serviceMock : new UserService($this->model);
        ;

        $this->session = Session::getInstance();
    }

    /**
     * Get user inputs from register screen and Registers a new user.
     *
     * @return bool true if success, else false
     */
    public function registerUser()
    {


        //expected inputs labels
        $arrExpected = ['username', 'password', 'email', 'confirmPassword'];
//Get value of each inputs in variable named label(eg.  $username = 'test')
        foreach ($arrExpected as $value) {
            $$value = $_POST[$value] ?? '';
        }

        try {
//call register function to validate inputs and insert record in db
            if ($this->service->register($username, $email, $password, $confirmPassword)) {
                $message = "User registered successfully! Please login.";
                $this->render('login', ['generalMessage' => $message]);
                return true;
            } else {
                $error = $this->service->getErrors();
                $this->render('register', $error);
                return false;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        
             $this->render('register', ['error' => $error]);
        }
    }

    /**
     * Render Registration screen for user inputs
     *
     * @return void
     */
    public function register(): void
    {

        $this->render('register');
    }

    /**
     * get user inputs and Logs in a user and starts a session.
     *
     * @return bool true if success, else false
     */
    public function loginUser()
    {


        //expected inputs labels
        $arrExpected = ['username', 'password'];
//Get value of each inputs in variable named label(eg.  $username = 'test')
        foreach ($arrExpected as $value) {
            $$value = $_POST[$value] ?? '';
        }

        try {
            //call login function to validate inputs and insert record in db
            if ($this->service->login($username, $password)) {
                $dashboard = $this->service->getDashboard();
                header("Location:$dashboard");
                exit;
            } else {
                throw new Exception("Invalid credentials", 401);
               // $error = $this->service->getErrors();
               // $this->render('login', $error);
               // return false;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
             $this->render('login', ['error' => $error]);
        }
    }

    /**
     * Render login screen for user inputs
     *
     * @return void
     */
    public function login(): void
    {

        $this->render('login');
    }

    /**
     * Logs out the user by destroying the session.
     *
     * @return void
     */
    public function logout()
    {
        $this->session->destroy();
        $this->render('login', ['generalMessage' => 'Logout successfully.']);
        return true;
    }
}
