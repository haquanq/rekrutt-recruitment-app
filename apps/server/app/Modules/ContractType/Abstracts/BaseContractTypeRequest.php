<?php

namespace App\Modules\ContractType\Abstracts;

use App\Modules\ContractType\Models\ContractType;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseContractTypeRequest extends FormRequest
{
    protected ?ContractType $contractType;

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
            "description" => ["nullable", "string", "max:500"],
        ];
    }

    public function getContractTypeOrFail(string $param = null): ContractType
    {
        if ($this->contractType === null) {
            $this->contractType = ContractType::findOrFail($this->route($param ?? "id"));
        }

        return $this->contractType;
    }
}
