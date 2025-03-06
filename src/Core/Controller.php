<?php

namespace HRMS\core;

use HRMS\Core\Session;

/**
 * Class Controller
 *
 * Base Controller class for the PHP MVC framework.
 * All other controllers should extend this class.
 *
 * @package App\Core
 */
class Controller
{
    private $session;
    
    /**
     * Loads a view and passes data to it.
     *
     * @param string $view Name of the view file to load.
     * @param array $data Data to be extracted and passed to the view.
     * @return void
     */
    protected function render($view, $data = [])
    {
        $viewPath = ROOT . "Views/" . $view . ".php";
        $this->session = Session::getInstance();

        //Check if view file exists or not
        if (file_exists($viewPath)) {
            extract($data);
            
            //If admin pages, add admin header
            if($this->session->get('role')=='admin')
            {
                include ROOT . "Views/admin/header.php";
                include ROOT . "Views/$view.php";
            }
            elseif($this->session->get('role')=='patient')  //If patient pages, add common header and footer
            {
                include ROOT . "Views/header.php";
                include $viewPath;
                include ROOT . "Views/footer.php";
            }
            else{   // before login pages (registration and login)
                include ROOT . "Views/$view.php";
            }

        } else {
            throw new \Exception("View $view not found.");
        }

    }
}