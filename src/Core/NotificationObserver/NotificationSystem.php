<?php
namespace HRMS\Observers;


class NotificationSystem {
    private $observers = [];

    public function addObserver(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function notifyObservers(string $message) {
        foreach ($this->observers as $observer) {
            $observer->update($message);
        }
    }
}
?>
