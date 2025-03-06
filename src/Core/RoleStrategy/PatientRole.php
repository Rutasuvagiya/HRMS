<?php
namespace HRMS\Core;

/**
 * Class PatientRole
 * Defines the dashboard view for Patient.
 */
class PatientRole implements UserRoleStrategy {
    public function getDashboard() {
        return "dashboard";
    }
}
?>
