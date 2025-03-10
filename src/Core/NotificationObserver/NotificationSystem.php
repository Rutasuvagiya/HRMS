<?php

namespace HRMS\Observers;

/**
 * Class NotificationSystem
 *
 * Implements the Observer pattern for managing notifications.
 */
class NotificationSystem
{
    //Stores the list of registered observers.
    private $observers = [];

    /**
     * Adds an observer to the notification system.
     *
     * @param Observer $observer The observer instance to be added.
     * @return void
     */
    public function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    /**
     * Notifies all registered observers with the latest message.
     *
     * @param string $message message to deliver to all the observers.
     * @return void
     */
    public function notifyObservers(string $message)
    {
        foreach ($this->observers as $observer) {
            $observer->update($message);
        }
    }
}
