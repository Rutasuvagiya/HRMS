<?php

namespace HRMS\core;

use HRMS\Core\Session;

class Controller
{
    private $session;
    
    
   
    protected function render($view, $data = [])
    {
        $this->session = Session::getInstance();
        extract($data);
       
        if($this->session->get('role')=='admin')
        {
            include ROOT . "Views/admin/header.php";
            include ROOT . "Views/$view.php";
        }
        elseif($this->session->get('role')=='patient')
        {
            include ROOT . "Views/header.php";
            include ROOT . "Views/$view.php";
            include ROOT . "Views/footer.php";
        }
        else{
            include ROOT . "Views/$view.php";
        }
        exit;
        
    
    }
}