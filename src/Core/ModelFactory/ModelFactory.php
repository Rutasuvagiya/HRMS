<?php
namespace HRMS\Factories;

/**
 * Class ModelFactory
 *
 * Factory class to create model instances dynamically in the Health Record Management System.
 */
class ModelFactory {

    /**
     * Creates and returns an instance of the requested model.
     * 
     * @param string $model The name of the model class to create.
     * @return object The created model instance.
     * @throws Exception If the model does not exist.
     */
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
