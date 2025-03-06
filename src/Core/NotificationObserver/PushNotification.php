<?php
namespace HRMS\Observers;
use HRMS\Core\Session;

class PushNotification implements Observer
{
    private $session;

    public function __construct() {
        $this->session = Session::getInstance();
    }

    public function update(string $message)
    {
        if(trim($message) !== '')
        {
            $sessionMsg = $this->session->get('notifications');
            // Send push notification to patient
            $sessionMsg .=  $message . ":::";
            
            $this->session->set('notifications', $sessionMsg);
        }
       
    }
}
