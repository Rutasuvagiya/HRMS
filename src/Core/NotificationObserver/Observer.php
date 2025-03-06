<?php
namespace HRMS\Observers;

/**
 * Interface Observer
 *
 * Defines the contract for all observers in the notification system.
 */
interface Observer {
    /**
     * Receives update notifications from the subject.
     *
     * @param string $message The notification message.
     * @return void
     */
    public function update(string $message);
}
?>
