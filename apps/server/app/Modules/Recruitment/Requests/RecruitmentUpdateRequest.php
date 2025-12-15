<?php

namespace App\Modules\Recruitment\Requests;

use App\Modules\Recruitment\Abstracts\BaseRecruitmentRequest;
use Illuminate\Support\Facades\Gate;

class RecruitmentUpdateRequest extends BaseRecruitmentRequest
{
    public function authorize(): bool
    {
        Gate::authorize("update", $this->getQueriedRecruitmentOrFail());
        return true;
    }
}
