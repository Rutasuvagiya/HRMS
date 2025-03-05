<?php

namespace HRMS;

use HRMS\Session;

class Controller
{
    private $session;
    
    
   
    protected function render($view, $data = [])
    {
        $this->session = Session::getInstance();
        extract($data);
        
        if($this->session->get('role')=='admin')
        {
            include "Views/admin/header.php";
            include "Views/$view.php";
        }
        elseif($this->session->get('role')=='patient')
        {
            include "Views/header.php";
            include "Views/$view.php";
            include "Views/footer.php";
        }
        else{
            include "Views/$view.php";
        }
        
        
    
    }
}