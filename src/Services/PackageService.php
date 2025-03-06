<?php

namespace HRMS\services;

use HRMS\Core\Validator;
use HRMS\Core\Notifier;

class PackageService{
    private Validator $validator;
    private $model;
    private $notifier;
    private $errors = [];

    public function __construct( $model) {
        $this->model =$model;
        $this->validator = new Validator();
        $this->notifier = Notifier::getInstance();
    }

    public function savePackage($name, $price, $validity)
    {
        $this->validator->validateRequiredFields(['name'=>$name, 'price'=>$price, 'validity'=>$validity]);
        $this->validator->validateName($name, 'name');
        $this->validator->isNumber($email);
        $this->validator->isFloatNumber($price);

        if (empty($this->validator->getErrors())) {
        
            if ($this->model->savePackage($name, $price, $validity)) {
                $this->errors['generalMessage'] = "Package saved successfully.";
                return true;
            } else {
                $this->errors['generalMessage'] = "Data insertion failed!";
                return false;
            }
           
        }else {
            $error = $this->validator->getErrors(); // Display errors
            $this->errors['error'] = $error;
            return false;
        }
    }

    public function upgradePackage($userId, $packageId)
    {
        $packages = $this->model->getPackageList($packageId);
        
        $validity = $packages[0]['validity'];
        $startDate = date('Y-m-d H:i:s'); // Extending by 30 days
        $newExpiryDate = date('Y-m-d', strtotime("+$validity day")); // Extending by 30 days

        if ($this->model->upgradePackage($userId, $packageId, $startDate, $newExpiryDate)) {
            
            $this->notifier->addNotification("Package upgraded successfully!");
            header('Location: viewMyPackage');
            exit;
        } else {
            $this->errors['generalMessage'] = "Error upgrading package!";
            return false;
        }
    }
}