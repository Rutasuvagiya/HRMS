<?php

namespace HRMS\services;

use HRMS\Core\Validator;
use HRMS\Core\Notifier;

/**
 * Class PackageService
 *
 * Provides business logic for package management in the Health Record Management System.
 */
class PackageService{
    private Validator $validator;
    private $model;
    private $notifier;
    private $errors = [];

    /**
     * Constructor to initialize the package model, validator to validate inputs and notifier to push notification
     * 
     * @param PackageModel $model The Package model instance.
     */
    public function __construct( $model) {
        $this->model = $model;
        $this->validator = new Validator();
        $this->notifier = Notifier::getInstance();
    }

    /**
     * Insert new package in database with validations
     * 
     * @param string $name name of package
     * @param float $price price of package
     * @param int $validity number of days
     * @return bool|string True if successful, error message otherwise.
     */
    public function savePackage($name, $price, $validity)
    {
        //check all the required fields are not empty
        $this->validator->validateRequiredFields(['name'=>$name, 'price'=>$price, 'validity'=>$validity]);
        //validate name of package
        $this->validator->validateName($name, 'name');
        //check is validity whole number or not
        $this->validator->isNumber($validity, 'validity');
        //check price is float value
        $this->validator->isFloatNumber($price, 'price');

        try {
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
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Update package details for given user ID
     * 
     * @param int $userId user id
     * @param int $packageId package id
     * @return Void|string void if success, error message otherwise.
     */
    public function upgradePackage($userId, $packageId)
    {
        try{
            //Get package details from package id
            $packages = $this->model->getPackageList($packageId);
            //Get validity of given package id
            $validity = $packages[0]['validity'];
            //Set start day of today
            $startDate = date('Y-m-d H:i:s'); 
            //Set exprity date from start day+validity
            $newExpiryDate = date('Y-m-d', strtotime("+$validity day")); 

            //update package details in database
            if ($this->model->upgradePackage($userId, $packageId, $startDate, $newExpiryDate)) {
                
                $this->notifier->addNotification("Package upgraded successfully!");
                header('Location: viewMyPackage');
                exit;
            } else {
                $this->errors['generalMessage'] = "Error upgrading package!";
                return false;
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}