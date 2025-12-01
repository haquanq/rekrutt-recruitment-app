<?php

namespace App\Modules\Position\Abstracts;

use App\Abstracts\BaseFormRequest;

abstract class BasePositionRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = \intval($this->route("id"));

        return [
            /**
             * Position title (must be unique)
             * @example Data Analyst
             */
            "title" => ["required", "string", "max:100", "unique:position,title,$id"],

            /**
             * Position description
             * @example Collects, cleans, and interprets data to identify trends and patterns that help organizations make better business decisions
             */
            "description" => ["string", "max:500"],

            /**
             * Department Id where position belongs
             * @example 1
             */
            "department_id" => ["required", "integer", "exists:department,id"],
        ];
    }
}
