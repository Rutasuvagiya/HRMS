<?php
namespace HRMS\Controllers;

use HRMS\Core\Controller;
use HRMS\Services\AdminService;
use HRMS\Models\ModelFactory;
use HRMS\Core\Session;


class AdminController  extends Controller {
    private AdminService $service;
    private $healthRecordModel;
    private $adminModel;
    private $packageModel;
    

    public function __construct() {
        $this->healthRecordModel = ModelFactory::create('HealthRecordModel');//new HealthRecordModel();
        $this->adminModel = ModelFactory::create('AdminModel');//new AdminModel();
        $this->packageModel = ModelFactory::create('PackageModel');//new AdminModel();
        $this->service = new AdminService($this->adminModel, $this->healthRecordModel);
        $this->session = Session::getInstance();;
        $this->session->checkAdminSession();
        
    }

    public function getAdminDashboardData()
    {
        $healthRecords = $this->healthRecordModel->getAllRecords(10);
        $packageRecords = $this->packageModel->getPackageList();
        
        $this->render('admin/dashboard', ['records'=>$healthRecords, 'package'=>$packageRecords]);
    }

    public function getPackages()
    {
        $packageRecords = $this->packageModel->getPackageList();
        
        $this->render('admin/packageList', ['records'=>$packageRecords]);
    }

    public function getAllPatientRecords()
    {
        $healthRecords = $this->healthRecordModel->getAllRecords(0);
       
        $this->render('admin/viewRecords', ['records'=>$healthRecords]);
    }

    public function getLog()
    {
        $id = $_GET['id'] ?? '';
        $this->service->getRecordLog($id);
       
    }
}