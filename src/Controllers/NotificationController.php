<?php

namespace HRMS\Controllers;

use HRMS\Core\Notifier;

/**
 * Class NotificationController
 *
 * Used to fetch notifications from session. This will be called from front page header via ajax call.
 */
class NotificationController
{
    /**
     * initialize the NotifierObserver and call getNotifications function to retieve notifications.
     *
     * @return void
     */
    public function getNotifications()
    {
        $notifier = Notifier::getInstance();
        $notifier->getNotifications();
    }
}
