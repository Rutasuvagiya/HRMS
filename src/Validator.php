<?php
namespace HRMS;

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

    public function getErrors(): array {
        return $this->errors;
    }

   
}
