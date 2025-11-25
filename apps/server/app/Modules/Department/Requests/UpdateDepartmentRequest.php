<?php

namespace App\Modules\Department\Requests;

use App\Modules\Department\Abstracts\BaseDepartmentRequest;

class UpdateDepartmentRequest extends BaseDepartmentRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        if ($this->method() == "PATCH") {
            foreach ($rules as $field => &$fieldRules) {
                array_splice($fieldRules, 0, \boolval($fieldRules[0] === "required"), "sometimes");
            }
        }
        return array_merge($rules, []);
    }
}
