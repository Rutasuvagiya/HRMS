<?php

namespace HRMS\Controllers;

use HRMS\Core\Controller;
use HRMS\Services\AdminService;
use HRMS\Factories\ModelFactory;
use HRMS\Core\Session;
use Exception;

/**
 * Class AdminController
 *
 * manage admin panel dashboard details and health record logs
 */
class AdminController extends Controller
{
    private AdminService $service;
    private $healthRecordModel;
    private $adminModel;
    private $packageModel;
    private $session;

    /**
     * Constructor to initialize the Health Record model, Admin model, Package model using a factory.
     * Initialize Admin Service and session.
     * Check session is for admin role or not
     *
     * @return void
     */
    public function __construct($sessionMock = null)
    {
        $this->healthRecordModel = ModelFactory::create('HealthRecordModel');
        $this->adminModel = ModelFactory::create('AdminModel');
        $this->packageModel = ModelFactory::create('PackageModel');
        $this->service = new AdminService($this->adminModel, $this->healthRecordModel);
        $this->session =  $sessionMock != null ? $sessionMock : Session::getInstance();
        $this->session->checkAdminSession();
//If session is not for admin, redirect to front dashboard
    }

    /**
     * Get list of top 10 health records and packages for dashboard
     *
     * @return  bool true if success, else false
     */
    public function getAdminDashboardData()
    {
        try {
//Get last 10 health records
            $healthRecords = $this->healthRecordModel->getAllRecords(10);
//Get all packages list
            $packageRecords = $this->packageModel->getPackageList();
            $this->render('admin/dashboard', ['records' => $healthRecords, 'package' => $packageRecords]);
            return true;
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->render('admin/dashboard', ['error' => $error]);
            return false;
        }
    }

    /**
     * Get list of all the packages to display on admin panel
     *
     * @return  bool true if success, else false
     */
    public function getPackages()
    {
        try {
//Get list of packages
            $packageRecords = $this->packageModel->getPackageList();
            $this->render('admin/packageList', ['records' => $packageRecords]);
            return true;
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->render('admin/packageList', ['error' => $error]);
            return false;
            
        }
    }

    /**
     * Get Health records of all the patients
     *
     * @return bool true if success, else false
     */
    public function getAllPatientRecords()
    {
        try {
//Get list of health records. Here argument 0 means all the records
            $healthRecords = $this->healthRecordModel->getAllRecords(0);
            $this->render('admin/viewRecords', ['records' => $healthRecords]);
            return true;
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->render('admin/viewRecords', ['error' => $error]);
            return false;
        }
    }

    /**
     * Get log of selected health record
     *
     * @return string log table generated by service method or error
     */
    public function getLog()
    {
        $id = $_GET['id'] ?? '';
        try {
        //Get log for id
            $this->service->getRecordLog($id);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
