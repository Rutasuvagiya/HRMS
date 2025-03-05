<?php
namespace HRMS\Controllers;

use HRMS\Controller;
use HRMS\Services\HealthRecordService;
use HRMS\Models\ModelFactory;
use HRMS\Session;


class HealthRecordController  extends Controller {
    private HealthRecordService $service;
    private  $packageModel;
    private  $model;

    public function __construct() {
        $this->model = ModelFactory::create('HealthRecordModel');
        $this->packageModel = ModelFactory::create('PackageModel');
        $this->service = new HealthRecordService($this->model);
        $this->session = Session::getInstance();;
        $this->session->checkSession(); // Redirects if session is not valid
    }


    public function submitHealthRecord() {
       
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $arrExpected = ['id', 'patient_name', 'age', 'gender', 'allergies', 'medications'];
           
            foreach($arrExpected as $value)
            {
                $input[$value] = $$value = $_POST[$value]??'';
            }

            if($this->service->submitHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $_FILES['attachment'])) {
                header('Location: dashboard');
                exit;
            } else {
                $error = $this->service->getErrors();
                $error['input'] =$input;
                
                $this->render('addRecords', $error);
            }

        }
    }

    public function getUserWiseHealthRecords()
    {
        $message = '';
         $count = $this->packageModel->getExpiringPackages();
        if($count >0):
            $message =  "Your package is expiring in next $count days.";
        elseif($count === 0):
            $message =  "Your package is expiring today.";
        elseif($count < 0):
            $message =  "Your package is expired.";
        endif;

        $healthRecords = $this->model->getUserWiseHealthRecords();
        $this->render('recordList', ['records'=>$healthRecords, 'generalMessage'=>$message]);
    }

    public function addRecord(): void{
        $this->render('addRecords');
    }

    public function editRecord(): void{
        if(isset($_GET['id']))
        {
            $healthRecords = $this->model->getHealthRecordByID($_GET['id']);
            $error['input'] =$healthRecords;
                
            $this->render('addRecords', $error);
        }
        else{
        $this->render('404');
        }
    }

    

}
