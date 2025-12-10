<?php

namespace App\Modules\ContractType\Requests;

use App\Modules\ContractType\Abstracts\BaseContractTypeRequest;
use App\Modules\ContractType\Models\ContractType;
use Illuminate\Support\Facades\Gate;

class ContractTypeUpdateRequest extends BaseContractTypeRequest
{
    public function authorize(): bool
    {
        Gate::authorize("update", ContractType::class);
        return true;
    }
}
