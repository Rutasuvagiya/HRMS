<?php
namespace HRMS\Core;

/**
 * Class AdminRole
 * 
 * Defines the dashboard view for Admin.
 */
class AdminRole implements UserRoleStrategy {

    /**
    * Get the dashboard view of Admin.
    *
    * @return string Admin dashboard page.
    */
    public function getDashboard() {
        return "adminDashboard";
    }
}
?>
