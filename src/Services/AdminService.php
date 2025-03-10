<?php

namespace HRMS\services;

use HRMS\Core\Validator;

/**
 * Class AdminService
 *
 * Handles admin panel dashboard and log activities for HRMS
 */
class AdminService
{
    private $userRepository;
    private Validator $validator;
    private $adminModel;
    private $healthRecordModel;
    private $errors = [];

    /**
     * Constructor to initialize the AdminModel and HealthRecordModel and validator - validate inputs,
     *
     * @param AdminModel $adminModel The Admin model instance.
     * @param HealthRecordModel $healthRecordModel The Health record model instance.
     */
    public function __construct($adminModel, $healthRecordModel)
    {
        $this->adminModel = $adminModel;
        $this->validator = new Validator();
        $this->healthRecordModel = $healthRecordModel;
    }

    /**
     * Get Logs of given record ID.
     * This method is called from ajax and prints html directly in popup in admin panel.
     *
     * @param int $id health record Id
     * @return string html code with log details
     */
    public function getRecordLog($id)
    {
        //Get health record details from given id
        $record = $this->healthRecordModel->getHealthRecordByID($id);

        //If attachment is there add link else print no attachment message in list
        if (!empty($record['attachment'])) :
            $attachment = "<a href='" . htmlspecialchars($record['attachment']) . "' target='_blank'>View</a>";
        else :
            $attachment =  "No Attachment";
        endif;

        //Convert date into user readable format
        $date = date('Y-m-d H:i:s', strtotime($record['created_at']));

         $string = <<<STT
                <table>
                    <tr>
                        <th>Patient Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Allergies</th>
                        <th>Medication</th>
                        <th>Attachment</th>
                        <th>Created Date</th>
                    </tr>
                    <tr>
                        <td> {$record['patient_name']} </td>
                        <td> {$record['age']} </td>
                        <td> {$record['gender']} </td>
                        <td> {$record['allergies']} </td>
                        <td> {$record['medications']} </td>
                        <td> {$attachment}</td>
                        <td> {$date} </td>
                    </tr>
                </table>
            STT;

        $recordLog = $this->adminModel->getRecordLog($id);
        if (!empty($recordLog)) {
            $string .= <<< STT
                <table>
                    <tr>
                        <th>Changes</th>
                        <th>Time</th>
                    </tr>
            STT;
            foreach ($recordLog as $log) {
                $logs = explode(";", rtrim($log['changed_fields'], '; '));
                $data = "<ul>";
                foreach ($logs as $logData) {
                    $data .= "<li>$logData</li>";
                }
                $data .= "</ul>";
                $string .= "<tr><td>" . $data . "</td><td>" . date('Y-m-d H:i:s', strtotime($log['updated_at'])) . "</td></tr>";
            }
            echo $string .= "</table>";
        } else {
            echo $string .= "<table>
                    <tr><th>No Changes are done.</th></tr></table>";
        }
    }
}
