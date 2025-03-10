<?php

use PHPUnit\Framework\TestCase;
use HRMS\Observers\NotificationSystem;
use HRMS\Observers\Observer;

class NotificationSystemTest extends TestCase
{
    public function testObserverReceivesNotification()
    {
        $observerMock = $this->createMock(Observer::class);
        $observerMock->expects($this->once())->method('update')->with("New Health Record");

        $notificationSystem = new NotificationSystem();
        $notificationSystem->addObserver($observerMock);
        $notificationSystem->notifyObservers("New Health Record");
    }
}
