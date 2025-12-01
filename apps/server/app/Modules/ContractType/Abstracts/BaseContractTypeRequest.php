<?php

namespace App\Modules\ContractType\Abstracts;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseContractTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Name
             * @example 6 month contract
             */
            "name" => ["required", "string", "max:100"],
            /**
             * Description
             * @example 6 month remote work with in-house team
             */
            "description" => ["string", "max:500"],
        ];
    }
}
