<?php

namespace src\helper;

use Exception;

trait ValidationTrait
{

    /**
     * @param int|string $value
     * @param int|string $key
     * @return void
     * @throws Exception
     */
    private function string($value, $key): void
    {
        if (gettype($value) != 'string' || $value == "") {
            throw new Exception("Fehler: {$key} muss ein Text sein");
        }
    }

    /**
     * @param int|string $value
     * @param int|string $key
     * @return void
     * @throws Exception
     */
    private function integer($value, $key): void
    {
        $value = (int)$value;
        if ($value <= 0) {
            throw new Exception("Fehler: unerlaubter Wert für {$key}");
        }
    }

    /**
     * @param int|string $value
     * @param int|string $key
     * @return void
     * @throws Exception
     */
    private function double($value, $key): void
    {
        $value = (double)$value;
        if ($value <= 0) { //phpstan-ignore-line
            throw new Exception("Fehler: unerlaubter Wert für {$key}");
        }
    }

    /**
     * @phpstan-ignore-next-line
     * @param $model
     * @param array<int|string> $input
     * @return void
     */
    public function validateInput($model, $input): void
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
        } catch (Exception $e) { //@phpstan-ignore-line  Error is thrown in seperate message
            $previousPage = $_SESSION['previous'];
            $_SESSION['message'] = $e->getMessage();
            redirect($previousPage, 302, $input);
        }
    }
}
