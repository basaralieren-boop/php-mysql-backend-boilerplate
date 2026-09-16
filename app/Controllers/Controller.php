<?php
namespace App\Controllers;

use Core\Response;

abstract class Controller
{
    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }

    protected function getInput(): array
    {
        return array_merge($_GET, $this->getJsonInput());
    }

    protected function validate(array $data, array $rules): bool
    {
        $errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $rules_array = explode('|', $fieldRules);
            
            foreach ($rules_array as $rule) {
                if ($rule === 'required' && empty($data[$field])) {
                    $errors[$field][] = "$field is required";
                }
                
                if (strpos($rule, 'min:') === 0 && strlen($data[$field] ?? '') < (int)substr($rule, 4)) {
                    $errors[$field][] = "$field must be at least " . substr($rule, 4) . " characters";
                }
                
                if (strpos($rule, 'max:') === 0 && strlen($data[$field] ?? '') > (int)substr($rule, 4)) {
                    $errors[$field][] = "$field must not exceed " . substr($rule, 4) . " characters";
                }
                
                if ($rule === 'email' && !filter_var($data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "$field must be a valid email";
                }
            }
        }

        if (!empty($errors)) {
            Response::validation($errors);
            return false;
        }

        return true;
    }
}
?>
