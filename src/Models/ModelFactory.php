<?php
namespace HRMS\Models;

class ModelFactory {
    public static function create($model) {
        $modelClass = "HRMS\\Models\\" . ucfirst($model);
        
        if (class_exists($modelClass)) {
            return new $modelClass();
        } else {
            throw new \Exception("Model '$model' not found.");
        }
    }
}
?>
