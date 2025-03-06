<?php
namespace HRMS\Core;

use HRMS\Observers\NotificationSystem;
use HRMS\Observers\PushNotification;
use HRMS\Core\Session;

class Notifier {
    private $session;
    private static $instance = null;

    public function addNotification($message) {
       
        // Create notification system
        $notificationSystem = new NotificationSystem();

        // Add observers
        $notificationSystem->addObserver(new PushNotification());

        $notificationSystem->notifyObservers($message);


    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Notifier();
        }
        return self::$instance;
    }

    public function getNotifications()
    {
        // Check if notifications exist in session
        /*if (!isset($this->session->get('notifications'))) {
            $this->session->set('notifications', array());
        }
        $_SESSION['notifications'] = [];
*/
        $this->session = Session::getInstance();
        $message = $this->session->get('notifications');
        $messages = explode(':::', rtrim($message, ':::'));
        echo json_encode($messages);
    }

}
?>
