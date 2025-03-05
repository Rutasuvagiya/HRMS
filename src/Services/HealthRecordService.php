<?php

namespace HRMS\services;

use HRMS\Validator;

class HealthRecordService{
    private $userRepository;
    private Validator $validator;
    private $healthRecordModel;
    private $errors = [];

    public function __construct( $healthRecordModel) {
        $this->HealthRecordModel =$healthRecordModel;
        $this->validator = new Validator();
    }

    public function submitHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $attachment) 
    {
       
        $this->validator->validateRequiredFields(['patient_name'=>$patient_name, 'age'=>$age, 'allergies'=>$allergies]);
        

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
                
                    $this->errors['generalMessage'] = "Data updated successfully.";;
                    return true;
                } else {
                    $this->errors['generalMessage'] = "Data updation failed. Please try again.";;
                    return false;
                }
            }
            else
            {
                
                if ($this->HealthRecordModel->addHealthRecord($patient_name, $age, $gender, $allergies, $medications, $filePath)) {
                
                    $this->errors['generalMessage'] = "Data saved successfully.";;
                    return true;
                } else {
                    $this->errors['generalMessage'] = "Data insertion failed. Please try again.";;
                    return false;
                }
            }


            
            
        }else {
            $error = $this->validator->getErrors(); // Display errors
            $this->errors['error'] = $error;
            exit; return false;
        }
    }

    private function validateForm($data, $files) {
        $errors = [];

        if (empty($data['name']) || !preg_match("/^[a-zA-Z\s]+$/", $data['name'])) {
            $errors['name'] = "Valid name is required.";
        }

        if (empty($data['age']) || !filter_var($data['age'], FILTER_VALIDATE_INT)) {
            $errors['age'] = "Valid age is required.";
        }

        if (!in_array($data['gender'], ["Male", "Female", "Other"])) {
            $errors['gender'] = "Invalid gender selection.";
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Valid email is required.";
        }

        if (empty($data['phone']) || !preg_match("/^[0-9]{10}$/", $data['phone'])) {
            $errors['phone'] = "Valid 10-digit phone number required.";
        }

        if (empty($data['diagnosis'])) {
            $errors['diagnosis'] = "Diagnosis is required.";
        }

        if (empty($files['attachment']['name'])) {
            $errors['attachment'] = "File attachment is required.";
        } else {
            $allowedTypes = ["application/pdf", "image/jpeg", "image/png"];
            if (!in_array($files['attachment']['type'], $allowedTypes)) {
                $errors['attachment'] = "Only PDF, JPG, or PNG files allowed.";
            }
        }

        return $errors;
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