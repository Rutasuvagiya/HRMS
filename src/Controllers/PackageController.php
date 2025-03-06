<?php
namespace HRMS\Controllers;

use HRMS\Core\Controller;
use HRMS\Services\PackageService;
use HRMS\Factories\ModelFactory;
use HRMS\Core\Session;

/**
 * Class PackageController
 * 
 * Handles package data. Insert, update, retrieve packages
 */
class PackageController  extends Controller {
    private PackageService $service;
    private $session;
    private $model;
    

    /**
     * Constructor to initialize the Package model using a factory.
     * Initialize Package Service and session.
     * Check session is valid
     */
    public function __construct() {
        $this->model = ModelFactory::create('PackageModel');
        $this->service = new PackageService($this->model);
        $this->session = Session::getInstance();
        $this->session->checkSession();
        
    }

    /**
     * Render add package screen for user inputs
     * 
     * @return void
     */
    public function addPackage()
    {
        $this->render('admin/addPackage');
    }

    /**
     * Get new package inputs from admin and save
     */
    public function savePackage()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            //expected inputs labels
            $arrExpected = ['name', 'price', 'validity'];
           
            //Get value of each inputs in variable named label(eg.  $username = 'test')
            foreach($arrExpected as $value)
            {
                $input[$value] = $$value = $_POST[$value]??'';
            }

            try{
                //call savePackage function to validate inputs and insert record in db
                if($this->service->savePackage($name, $price, $validity)) {
                    header('Location: viewPackages');
                    exit;
                } else {
                    $error = $this->service->getErrors();
                    $error['input'] =$input;
                    
                    $this->render('addRecords', $error);
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    /**
     * Get active package details for logged in user
     * 
     * @return void
     */
    public function getUserPackage()
    {
        $packageRecords = $this->model->getUserPackageList();
        
        $this->render('myPackageInfo', ['records'=>$packageRecords]);
    }

    /**
     * update package details for logged in user(user set in session)
     */
    public function upgradePackage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['package'])) {
            $userId = $this->session->get('userID');
            $packageId = $_POST['package'];
            
            try{
                //call upgradePackage function to validate inputs and update record in db
                if ($this->service->upgradePackage($userId, $packageId)) {
                    header('Location: viewMyPackage');
                    exit;
                } else {
                    $error = $this->service->getErrors();
                    
                    $this->render('upgradePackageForm', $error);
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
            
        }
    }

    // Display available packages
    public function showUpgradeForm() {
        $packages = $this->model->getPackageList();
 
        $this->render('upgradePackageForm', ['records'=>$packages]);
    }

    
}

