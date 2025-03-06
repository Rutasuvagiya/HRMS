<?php
namespace HRMS\Core;

/**
 * Interface UserRoleStrategy
 * 
 * Defines a method for getting the dashboard view.
 */
interface UserRoleStrategy {
    
    /**
    * Get the dashboard view for the specific role.
    *
    * @return string Dashboard page or message.
    */
    public function getDashboard();
}
?>
