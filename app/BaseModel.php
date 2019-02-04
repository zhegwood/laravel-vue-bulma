<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    //
    private $errors = [];
    protected function addError($error)
    {
        $this->errors[] = $error;
    }
    public function getErrors($as_array = false)
    {
        if ($as_array) {
            return $this->errors;
        }
        return implode('<br/>', $this->errors);
    }
}