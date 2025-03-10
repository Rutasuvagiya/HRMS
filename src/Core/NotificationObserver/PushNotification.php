<?php

namespace HRMS\Observers;

use HRMS\Core\Session;

/**
 * Class PushNotificationObserver
 *
 * Sends push notifications to the frontend.
 */
class PushNotification implements Observer
{
    private $session;

    public function __construct()
    {
        $this->session = Session::getInstance();
    }

    /**
     * stores notification message in session from NotificationSystem.
     *
     * @param string $message message to send in notification.
     * @return void
     */
    public function update(string $message)
    {
        if (trim($message) !== '') {
//Get current notifications from session
            $sessionMsg = $this->session->get('notifications');
//Append new message in existing notification messages with ::: seperator.
            $sessionMsg .=  $message . ":::";
//Set message in session
            $this->session->set('notifications', $sessionMsg);
        }
    }
}
