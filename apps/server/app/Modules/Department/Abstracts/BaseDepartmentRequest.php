<?php

namespace App\Modules\Department\Abstracts;

use App\Abstracts\BaseFormRequest;
use App\Modules\Department\Models\Department;

abstract class BaseDepartmentRequest extends BaseFormRequest
{
    protected ?Department $department = null;

    public function rules(): array
    {
        return [
            /**
             * Name
             * @example "Research and Development"
             */
            "name" => ["required", "string", "max:100"],
            /**
             * Description
             * @example "Research something"
             */
            "description" => ["nullable", "string", "max:500"],
        ];
    }

    public function getDepartment(string $param = null): ?Department
    {
        if ($this->department === null) {
            $this->department = Department::findOrFail($this->route($param ?? "id"));
        }

        return $this->department;
    }
}
