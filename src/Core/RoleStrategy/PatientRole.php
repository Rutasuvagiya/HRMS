<?php
namespace HRMS\Core;

/**
 * Class PatientRole
 * 
 * Defines the dashboard view for Patient.
 */
class PatientRole implements UserRoleStrategy {

    /**
    * Get the dashboard view of Admin.
    *
    * @return string Patient dashboard page.
    */
    public function getDashboard() {
        return "dashboard";
    }
}
?>
