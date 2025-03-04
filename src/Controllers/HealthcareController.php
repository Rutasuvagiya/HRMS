<?php
namespace HRMS\Controllers;

use HRMS\Controller;
use HRMS\Services\UserService;
use HRMS\Validator;
use HRMS\Models\UserModel;


class HealthcareController  extends Controller {
    private UserService $user;
    

    public function __construct() {
       // $this->user = new UserService(new UserModel(), new Validator());
        
    }

    public function addRecord(): void{
        $this->render('addRecords');
    }

}
