<?php

namespace HRMS\services;

use HRMS\Validator;

class AdminService{
    private $userRepository;
    private Validator $validator;
    private  $adminModel;
    private  $healthRecordModel;
    private $errors = [];

    public function __construct( $adminModel, $healthRecordModel) {
        $this->adminModel =$adminModel;
        $this->validator = new Validator();
        $this->healthRecordModel = $healthRecordModel;
        
    }

    public function getRecordLog($id)
    {
        $record = $this->healthRecordModel->getHealthRecordByID($id);

        if (!empty($record['attachment'])):
            $attachment = "<a href='". htmlspecialchars($record['attachment']) ."' target='_blank'>View</a>";
        else:
            $attachment =  "No Attachment";
        endif;

        $date = date('Y-m-d H:i:s', strtotime($record['created_at']));

         $string= <<<STT
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
        if(!empty($recordLog))
        {
            $string .= <<< STT
                <table>
                    <tr>
                        <th>Changes</th>
                        <th>Time</th>
                    </tr>
            STT;
            foreach($recordLog as $log)
            {
                $logs = explode(";" ,rtrim($log['changed_fields'],'; '));
                $data ="<ul>";
                foreach($logs as $logData)
                {
                    $data .= "<li>$logData</li>";
                }
                $data .="</ul>";
                $string .= "<tr><td>" . $data . "</td><td>".date('Y-m-d H:i:s', strtotime($log['updated_at']))."</td></tr>";
            }
            echo $string .= "</table>";
            
        }
        else
        {
            echo $string .= "<table>
                    <tr><th>No Changes are done.</th></tr></table>";
        }
      
        
    }
}