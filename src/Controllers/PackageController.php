<?php
namespace HRMS\Controllers;

use HRMS\Controller;
use HRMS\Services\PackageService;
use HRMS\Models\ModelFactory;
use HRMS\Session;


class PackageController  extends Controller {
    private PackageService $service;
    private $session;
    private $model;
    

    public function __construct() {
        $this->model = ModelFactory::create('PackageModel');
        $this->service = new PackageService($this->model);
        $this->session = Session::getInstance();
        $this->session->checkSession();
        
    }

    public function addPackage()
    {
        $this->render('admin/addPackage');
    }

    public function savePackage()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $arrExpected = ['name', 'price', 'validity'];
           
            foreach($arrExpected as $value)
            {
                $input[$value] = $$value = $_POST[$value]??'';
            }

            if($this->service->savePackage($name, $price, $validity)) {
                header('Location: viewPackages');
                exit;
            } else {
                $error = $this->service->getErrors();
                $error['input'] =$input;
                
                $this->render('addRecords', $error);
            }

        }
    }

    public function getUserPackage()
    {
        $packageRecords = $this->model->getUserPackageList();
        
        $this->render('myPackageInfo', ['records'=>$packageRecords]);
    }

    public function upgradePackage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['package'])) {
            $userId = $this->session->get('userID');
            $packageId = $_POST['package'];
            
            if ($this->service->upgradePackage($userId, $packageId)) {
                header('Location: viewMyPackage');
                exit;
            } else {
                $error = $this->service->getErrors();
                
                $this->render('upgradePackageForm', $error);
            }
        }
    }

    // Display available packages
    public function showUpgradeForm() {
        $packages = $this->model->getPackageList();
 
        $this->render('upgradePackageForm', ['records'=>$packages]);
    }

    
}

