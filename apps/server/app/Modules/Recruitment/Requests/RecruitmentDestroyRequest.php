<?php

namespace App\Modules\Recruitment\Requests;

use App\Modules\Recruitment\Abstracts\BaseRecruitmentRequest;
use Illuminate\Support\Facades\Gate;

class RecruitmentDestroyRequest extends BaseRecruitmentRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", $this->getQueriedRecruitmentOrFail());
        return true;
    }
}
