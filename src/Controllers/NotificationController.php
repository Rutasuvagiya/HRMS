<?php
namespace HRMS\Controllers;
use HRMS\Core\Session;

class NotificationController {
    private $session;


    public function getNotifications()
    {
        $this->session = Session::getInstance();
        $message = $this->session->get('notifications');
        $messages = explode(':::', rtrim($message, ':::'));
        echo json_encode($messages);
    }

}
?>
