<?php

namespace App\Modules\ContractType\Requests;

use App\Modules\ContractType\Abstracts\BaseContractTypeRequest;
use App\Modules\ContractType\Models\ContractType;
use Illuminate\Support\Facades\Gate;

class ContractTypeDestroyRequest extends BaseContractTypeRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", ContractType::class);
        return true;
    }
}
