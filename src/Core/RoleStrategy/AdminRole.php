<?php
namespace HRMS\Core;

/**
 * Class AdminRole
 * Defines the dashboard view for Admin.
 */
class AdminRole implements UserRoleStrategy {
    public function getDashboard() {
        return "adminDashboard";
    }
}
?>
