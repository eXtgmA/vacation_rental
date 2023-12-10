<?php

namespace src\helper;

use Exception;

trait ValidationTrait
{

    private function string($value, $key)
    {
        if (gettype($value) != 'string'|| $value=="") {
            throw new Exception("Fehler: {$key} muss ein Text sein");
        }
    }
    private function integer($value, $key)
    {
        $value = (int)$value;
        if (gettype($value) != 'integer' || $value<=0) {
            throw new Exception("Fehler: unerlaubter Wert für {$key}");
        }
    }
    private function double($value, $key)
    {
        $value = (double)$value;
        if (gettype($value) != 'double' || $value<=0) {
            throw new Exception("Fehler: unerlaubter Wert für {$key}");
        }
    }

    public function validateInput($model, $input)
    {
        $model = '\src\models\\' . $model;
        $rules = $model::$rules;
        try {
            foreach ($input as $key => $value) {
                if (array_key_exists($key, $rules)) {
                    // Existing Key in Input and Rules found, start validating
                    $rule = $rules[$key];
                    $rule = $rule[0];
                    $this->$rule($value, $key);
                }
            }
            return;
        } catch (Exception $e) {
            $previousPage = $_SESSION['previous'];
            $_SESSION['message'] = $e->getMessage();
            redirect($previousPage, 302,$input);
            die();
        }
    }

}
