<?php

namespace App\Modules\Recruitment\Requests;

use App\Modules\Recruitment\Abstracts\BaseRecruitmentApplicationRequest;
use App\Modules\Recruitment\Models\RecruitmentApplication;
use Illuminate\Support\Facades\Gate;

class RecruitmentApplicationDestroyRequest extends BaseRecruitmentApplicationRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        Gate::authorize("delete", RecruitmentApplication::class);
        return true;
    }
}
