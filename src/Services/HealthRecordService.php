<?php

namespace HRMS\services;

use HRMS\Core\Validator;
use HRMS\Core\Notifier;

/**
 * Class HealthRecordService
 *
 * Provides business logic for Health records (validations and data update) in the Health Record Management System.
 */
class HealthRecordService{
    private $userRepository;
    private Validator $validator;
    private $healthRecordModel;
    private $notifier;
    
    private $errors = [];

    /**
     * Constructor to initialize the  HealthRecordModel and validator tp validate inputs and notifier to push notifications.
     * 
     * @param HealthRecordModel $healthRecordModel The Health record model instance.
     */
    public function __construct( $healthRecordModel) {
        $this->HealthRecordModel =$healthRecordModel;
        $this->validator = new Validator();
        $this->notifier = Notifier::getInstance();
    }

    /**
     * validate health record inputs and save/update in database
     * 
     * @param int|null $id int for update record, null for new record
     * @param string $patient_name name of patient
     * @param float $age patient age
     * @param string $gender patient gender.
     * @param string $allergies allergies details.
     * @param string $medications Prescription details.
     * @param string $filePath File path of an attachment.
     * @return bool|string True if successful, error message otherwise.
     *  
     */
    public function submitHealthRecord($id, $patient_name, $age, $gender, $allergies, $medications, $attachment) 
    {
       
        $this->validator->validateRequiredFields(['patient_name'=>$patient_name, 'age'=>$age, 'allergies'=>$allergies]);
        $this->validator->isFloatNumber($age, 'age');
        $this->validator->checkInArray($gender, ['Male', 'Female', 'Other'] , 'gender');
        $this->validator->validateAttachment($_FILES['attachment'], 'attachment');
        
        try{
            if (empty($this->validator->getErrors())) {
                
                $filePath = '';
                //If file is not null, upload file and get file path
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
                    //update health record in database
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
                    //insert new health record
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
        }  catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

   
    /**
     * uploads attached file into server 
     * 
     * @param array $file attachment
     * @return string|bool filepath if success, else false
     */
    private function handleFileUpload($file) {
        $uploadDir = "uploads/";
        $fileName = time() . "_" . basename($file["name"]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        }
        return false;
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