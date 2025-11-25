<?php

namespace App\Modules\Department\Requests;

use App\Modules\Department\Abstracts\BaseDepartmentRequest;

class StoreDepartmentRequest extends BaseDepartmentRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }
}
