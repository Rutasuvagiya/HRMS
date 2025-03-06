<?php
namespace HRMS\core;

class Validator {
    private array $errors = [];


    /**
     * Validate required fields in an associative array
     * @param array $data - Input data (e.g., $_POST)
     * @param array $requiredFields - List of required fields
     * @return bool - Returns true if all required fields are present and not empty
     */
    public function validateRequiredFields(array $requiredFields): bool {
        
        foreach ($requiredFields as $key => $value) {
            if (!isset($value) || trim($value) === '') {
               $this->errors[$key] = "This field is required.";
            }
        }
        return empty($this->errors);
    }


    public function validateName(string $name, $label): bool {
        if (!preg_match("/^[a-zA-Z0-9_\-]{3,50}$/", $name)) {
            $this->errors[$label] = "Invalid name. Name must be alphanumeric and at least 3 characters.";
            return false;
        }
        return true;
    }

    public function validateEmail(string $email, $label): bool {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$label] = "Invalid email format.";
            return false;
        }
        return true;
    }

    public function isUsernameUnique($value, $label): bool {
        if ($value === true) {
            $this->errors[$label] = "Username already exists.";
            return false;
        }
        return true;
    }

    public function isNumber($value): bool{
        if (!filter_var($int, FILTER_VALIDATE_INT)) {
            $this->errors[$label] = "Please enter valid whole number.";
            return false;
        }
        return true;
    }

    public function isFloatNumber($value): bool{
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$label] = "Please enter valid $label.";
            return false;
        }
        return true;
    }

    public function validatePassword(string $password, string $confirmPassword, $label1, $label2): bool {
        if (strlen($password) < 6) {
            $this->errors[$label1] = "Password must be at least 6 characters.";
            return false;
        }
        if ($password !== $confirmPassword) {
            $this->errors[$label2] = "Passwords do not match.";
            return false;
        }
        return true;
    }

    public function checkInArray($value, $array, $lable)
    {
        if (!in_array($value, $array)) {
            $errors[$lable] = "Invalid $lable selection.";
        }
    }

    public function validateAttachment($file, $lable)
    {
        if($file['name'] == ''){
            return true;
        }
        // Allowed file types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        // Extract file details
         $fileName = basename($file['name']);
       echo $fileType = mime_content_type($file['tmp_name']);
        $fileSize = $file['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Check if file type is allowed
        if (!in_array($fileType, $allowedTypes) || !in_array($fileExt, $allowedExtensions)) {
            $this->errors[$lable] = "Invalid file type. Only JPG, PNG, GIF, and PDF are allowed.";
            return false;
        }

        // Check file size
        if ($fileSize > $maxFileSize) {
            $this->errors[$lable] = "File size must be less than 2MB.";
            return false;
        }
        return true;

    }

    public function getErrors(): array {
        return $this->errors;
    }

   
}
