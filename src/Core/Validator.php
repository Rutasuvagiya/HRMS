<?php

namespace HRMS\core;

/**
 * Class Validator
 *
 * Handles input validation for forms in system.
 */
class Validator
{
    /**
     * @var array Stores validation errors.
     */
    private array $errors = [];


    /**
     * Validate required fields in an associative array
     * @param array $requiredFields - List of required fields with label and value pair
     * @return bool - Returns true if all required fields are present and not empty
     */
    public function validateRequiredFields($requiredFields): bool
    {

        foreach ($requiredFields as $key => $value) {
            if (!isset($value) || trim($value) === '') {
                $this->errors[$key] = "This field is required.";
            }
        }
        return empty($this->errors);
    }


    public function validateName($name, $label): bool
    {
        if (!preg_match("/^[a-zA-Z0-9_\-]{3,50}$/", $name)) {
            $this->errors[$label] = "Invalid name. Name must be alphanumeric and at least 3 characters.";
            return false;
        }
        return true;
    }

    /**
     * Validates if a field is a valid email format.
     *
     * @param string $email The email value.
     * @param string $label The field name .
     * @return bool - Returns true if email is valid
     */
    public function validateEmail($email, $label): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$label] = "Invalid email format.";
            return false;
        }
        return true;
    }

    /**
     * Check if input is true then display already exists message
     *
     * @param boolean $value database value check and result in true or false.
     * @param string $label The field name .
     * @return bool - Returns true if username is valid
     */
    public function isUsernameUnique($value, $label): bool
    {
        if ($value === true) {
            $this->errors[$label] = "Username already exists.";
            return false;
        }
        return true;
    }

    /**
     * Check if input is integer
     *
     * @param string $value input field value
     * @param string $label The field name
     * @return bool - Returns true if input is integer
     */
    public function isNumber($value, $label): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$label] = "Please enter valid whole number.";
            return false;
        }
        return true;
    }

    /**
     * Check if input is float/decimal
     *
     * @param string $value input field value
     * @param string $label The field name
     * @return bool - Returns true if input is float
     */
    public function isFloatNumber($value, $label): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$label] = "Please enter valid $label.";
            return false;
        }
        return true;
    }

    /**
     * Check if password is valid or not
     *
     * @param string $password input password field value
     * @param string $confirmPassword  input confirm password field value
     * @param string $label1  input password field name
     * @param string $label2  input confirm password field value
     * @return bool - Returns true if passwords are valid
     */
    public function validatePassword($password, $confirmPassword, $label1, $label2): bool
    {
        //check if lenght of password is less then 6 then return false
        if (strlen($password) < 6) {
            $this->errors[$label1] = "Password must be at least 6 characters.";
            return false;
        }
        //Check password and confirmpasswords are equal
        if ($password !== $confirmPassword) {
            $this->errors[$label2] = "Passwords do not match.";
            return false;
        }
        return true;
    }

    /**
     * Check added value is exists in array or not
     *
     * @param string $value input field value
     * @param array $array expected values array
     * @param string $label  input field name
     * @return bool - Returns true if record exists in array
     */
    public function checkInArray($value, $array, $lable)
    {
        if (!in_array($value, $array)) {
            $this->errors[$lable] = "Invalid $lable selection.";
        }
    }

    /**
     * Check if attachment is valid or not
     *
     * @param file $file input attachment value
     * @param string $label  input field name
     * @return bool - Returns true if record exists in array
     */
    public function validateAttachment($file, $lable)
    {
        //If file name is null- file is not attached then return true
        if (!isset($file) || $file['name'] == '') {
            return true;
        }
        // Allowed file types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt'];
        $maxFileSize = 2 * 1024 * 1024;
// 2MB

        // Extract file details
         $fileName = basename($file['name']);
        $fileType = mime_content_type($file['tmp_name']);
        $fileSize = $file['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
// Check if file type is allowed
        if (!in_array($fileType, $allowedTypes) || !in_array($fileExt, $allowedExtensions)) {
            $this->errors[$lable] = "Invalid file type. Only JPG, PNG, GIF, TXT and PDF are allowed.";
            return false;
        }

        // Check file size
        if ($fileSize > $maxFileSize) {
            $this->errors[$lable] = "File size must be less than 2MB.";
            return false;
        }
        return true;
    }

    /**
     * Retrieves validation errors.
     *
     * @return array Returns an array of error messages.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
