<?php

namespace HRMS\Controllers;

use HRMS\Core\Controller;
use HRMS\Services\HealthRecordService;
use HRMS\Factories\ModelFactory;
use HRMS\Core\Session;

/**
 * Class HealthRecordController
 *
 * Handles Health record data. Insert, update, retrieve records
 */
class HealthRecordController extends Controller
{
    private HealthRecordService $service;
    private $model;
    private $session;

    /**
     * Constructor to initialize the Health Record model using a factory.
     * Initialize Health Record Service and session.
     * Check session is valid
     */
    public function __construct($sessionMock = null)
    {
        $this->model = ModelFactory::create('HealthRecordModel');
        $this->service = new HealthRecordService($this->model);
        $this->session = $sessionMock != null ? $sessionMock : Session::getInstance();
        $this->session->checkSession(); // Redirects if session is not valid
    }

    /**
     * Get health record inputs from front user and save
     */
    public function submitHealthRecord()
    {

        //expected inputs labels
        $arrExpected = ['id', 'patient_name', 'age', 'gender', 'allergies', 'medications'];
//Get value of each inputs in variable named label(eg.  $username = 'test')
        foreach ($arrExpected as $value) {
            $input[$value] = $$value = $_POST[$value] ?? '';
        }
        try {
//call submitHealthRecord function to validate inputs and insert record in db
            if ($this->service->submitHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $_FILES['attachment'])) {
                header('Location: dashboard');
                exit;
            } else {
                $error = $this->service->getErrors();
                $error['input'] = $input;
                $this->render('addRecords', $error);
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

     /**
     * Get Health records for logged in user
     *
     * @return void
     */
    public function getUserWiseHealthRecords()
    {
        try {
//call getUserWiseHealthRecords function get health records
            $healthRecords = $this->model->getUserWiseHealthRecords();
            $this->render('recordList', ['records' => $healthRecords]);
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Render add record screen for user inputs
     *
     * @return void
     */
    public function addRecord(): void
    {
        $this->render('addRecords');
    }

    /**
     * Get health record inputs from front user with record id to update
     */
    public function editRecord()
    {
        if (isset($_GET['id'])) {
            try {
            //call getHealthRecordByID function to validate inputs and update record in db
                $healthRecords = $this->model->getHealthRecordByID($_GET['id']);
                $error['input'] = $healthRecords;
                $this->render('addRecords', $error);
                return true;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        } else {
            $this->render('404');
            return false;
        }
    }
}
