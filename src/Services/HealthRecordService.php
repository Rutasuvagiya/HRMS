<?php

namespace HRMS\services;

use HRMS\Core\Validator;
use HRMS\Core\Notifier;

class HealthRecordService{
    private $userRepository;
    private Validator $validator;
    private $healthRecordModel;
    private $notifier;
    
    private $errors = [];

    public function __construct( $healthRecordModel) {
        $this->HealthRecordModel =$healthRecordModel;
        $this->validator = new Validator();
        $this->notifier = Notifier::getInstance();
    }

    public function submitHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $attachment) 
    {
       
        $this->validator->validateRequiredFields(['patient_name'=>$patient_name, 'age'=>$age, 'allergies'=>$allergies]);
        $this->validator->isFloatNumber($age, 'age');
        $this->validator->checkInArray($gender, ['Male', 'Female', 'Other'] , 'gender');
        $this->validator->validateAttachment($_FILES['attachment'], 'attachment');
        

        if (empty($this->validator->getErrors())) {
            
            $filePath = '';
            if(!empty($_FILES['attachment']['name'])){
                $filePath = $this->handleFileUpload($_FILES['attachment']);
                if (!$filePath) {
                
                    $this->errors['generalMessage'] = "File upload failed. Please try again.";
                    return false;
                }
            }
            
            //if update form is submitted. update records
            if(!is_null($id) && $id > 0)
            {
              
                if ($this->HealthRecordModel->updateHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $filePath)) {
                
                    $message = "Health Record updated successfully.";
                    $this->notifier->addNotification($message);
                    return true;
                } else {
                    $this->errors['generalMessage'] = "Data updation failed. Please try again.";;
                    return false;
                }
            }
            else
            {
                
                if ($this->HealthRecordModel->addHealthRecord($patient_name, $age, $gender, $allergies, $medications, $filePath)) {
                
                    $message = "Health Record inserted successfully.";
                    $this->notifier->addNotification($message);
                    return true;
                } else {
                    $this->errors['generalMessage'] = "Data insertion failed. Please try again.";;
                    return false;
                }
            }


            
            
        }else {
            $error = $this->validator->getErrors(); // Display errors
            $this->errors['error'] = $error;
             return false;
        }
    }

   

    private function handleFileUpload($file) {
        $uploadDir = "uploads/";
        $fileName = time() . "_" . basename($file["name"]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        }
        return false;
    }


    public function getErrors()
    {
        return  $this->errors;
    }


}