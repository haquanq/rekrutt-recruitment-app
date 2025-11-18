<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Auth\Abstracts\BaseUserRequest;

class UpdateUserRequest extends BaseUserRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        if ($this->method() == "PATCH") {
            foreach ($rules as $field => &$fieldRules) {
                array_splice($fieldRules, 0, \boolval($fieldRules[0] === "required"), "sometimes");
            }
        }
        return array_merge(parent::rules(), []);
    }
}
