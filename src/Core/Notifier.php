<?php

namespace HRMS\Core;

use HRMS\Observers\NotificationSystem;
use HRMS\Observers\PushNotification;
use HRMS\Core\Session;

/**
 * Class Notifier
 *
 * This class handles notifications(eg.- push notifications, mail, sms)
 * Singleton pattern is applied to this class to restrice multiple instance
 * centralized session based handler. established once and reused across the application.
 *
 */
class Notifier
{
    private $session;
    private static $instance = null;

    /**
     * Add new notification to observer's session.
     *
     * @param string $message Notification message to store in session
     * @return void
     */
    public function addNotification($message)
    {

        // Create notification system
        $notificationSystem = new NotificationSystem();
// Add observers
        $notificationSystem->addObserver(new PushNotification());
//Add message to Ober
        $notificationSystem->notifyObservers($message);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Notifier();
        }
        return self::$instance;
    }

    public function getNotifications()
    {
        $this->session = Session::getInstance();
        $message = $this->session->get('notifications');
        $messages = explode(':::', rtrim($message, ':::'));
        echo json_encode($messages);
    }
}
